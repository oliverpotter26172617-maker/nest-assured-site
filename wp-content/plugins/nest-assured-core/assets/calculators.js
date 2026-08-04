/**
 * Planning calculators.
 *
 * Everything runs locally. Nothing is submitted, stored or sent anywhere, which is
 * deliberate: these ask for household finances, so the figures should never leave
 * the visitor's browser.
 */
(function () {
  'use strict';

  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

  // Upper bounds so a mistyped figure produces an obviously bounded answer rather
  // than a confident nonsense one. A household does not have a billion-pound
  // mortgage, and eight million months is not a useful thing to tell anybody.
  var MAX_AMOUNT = 100000000;
  var MAX_MONTHS = 600;

  function toNumber(value) {
    var cleaned = String(value == null ? '' : value).replace(/[^0-9.]/g, '');
    var n = parseFloat(cleaned);
    if (!isFinite(n) || n <= 0) {
      return 0;
    }
    return Math.min(n, MAX_AMOUNT);
  }

  var money = new Intl.NumberFormat('en-GB', {
    style: 'currency',
    currency: 'GBP',
    maximumFractionDigits: 0
  });

  function plural(n, one, many) {
    return n === 1 ? one : many;
  }

  /**
   * Counts a figure up to its new value. The easing is deliberately weighted so
   * the number settles rather than stopping dead, and it is skipped entirely when
   * the visitor has asked for reduced motion.
   */
  function animateTo(el, from, to, format) {
    if (el._naFrame) {
      cancelAnimationFrame(el._naFrame);
      el._naFrame = null;
    }

    if (reduceMotion.matches || from === to) {
      el.textContent = format(to);
      return;
    }

    var duration = 520;
    var start = null;

    function step(timestamp) {
      if (start === null) {
        start = timestamp;
      }
      var progress = Math.min(1, (timestamp - start) / duration);
      // easeOutQuart: fast departure, long settle.
      var eased = 1 - Math.pow(1 - progress, 4);
      el.textContent = format(from + (to - from) * eased);
      if (progress < 1) {
        el._naFrame = requestAnimationFrame(step);
      } else {
        el._naFrame = null;
        el.textContent = format(to);
      }
    }

    el._naFrame = requestAnimationFrame(step);
  }

  function setBar(el, value, max) {
    if (!el) {
      return;
    }
    var pct = max > 0 ? Math.min(100, (value / max) * 100) : 0;
    el.style.width = pct.toFixed(1) + '%';
  }

  function val(root, id) {
    var el = root.querySelector('#' + id);
    return el ? toNumber(el.value) : 0;
  }

  function initCover(root) {
    var totalEl = root.querySelector('[data-na-calc-total]');
    var breakdownEl = root.querySelector('[data-na-calc-breakdown]');
    var needBar = root.querySelector('[data-na-calc-bar="need"]');
    var haveBar = root.querySelector('[data-na-calc-bar="have"]');
    var previous = 0;

    function recalculate() {
      var need = val(root, 'na-calc-mortgage')
        + val(root, 'na-calc-debts')
        + (val(root, 'na-calc-income') * val(root, 'na-calc-years'))
        + val(root, 'na-calc-costs');

      var have = val(root, 'na-calc-existing')
        + val(root, 'na-calc-work')
        + val(root, 'na-calc-savings');

      var gap = Math.max(0, need - have);
      var scale = Math.max(need, have);

      animateTo(totalEl, previous, gap, function (n) {
        return money.format(Math.round(n));
      });
      previous = gap;

      setBar(needBar, need, scale);
      setBar(haveBar, have, scale);

      if (need === 0) {
        breakdownEl.textContent = 'Enter your figures above to see the gap.';
        return;
      }

      if (gap === 0) {
        breakdownEl.textContent = 'On these figures what you already have covers what you listed. An adviser can check whether that cover would still be there when it was needed, since employer cover usually ends with the job.';
        return;
      }

      breakdownEl.textContent = money.format(Math.round(need))
        + ' would need covering and '
        + money.format(Math.round(have))
        + ' is already in place. The difference is a starting figure for a conversation, not a recommendation.';
    }

    root.addEventListener('input', recalculate);
    recalculate();
  }

  function initIncome(root) {
    var totalEl = root.querySelector('[data-na-calc-total]');
    var timelineEl = root.querySelector('[data-na-calc-timeline]');
    var breakdownEl = root.querySelector('[data-na-calc-breakdown]');
    var previous = 0;

    function recalculate() {
      var monthly = val(root, 'na-calc-monthly');
      var essential = val(root, 'na-calc-essential');
      var fullMonths = Math.round(val(root, 'na-calc-full-months'));
      var halfMonths = Math.round(val(root, 'na-calc-half-months'));
      var reserve = val(root, 'na-calc-reserve');

      timelineEl.innerHTML = '';

      if (monthly === 0 || essential === 0) {
        animateTo(totalEl, previous, 0, function (n) {
          return Math.round(n) + ' months';
        });
        previous = 0;
        breakdownEl.textContent = 'Enter your monthly pay and essential outgoings to map the timeline.';
        return;
      }

      var covered = fullMonths;
      var shortfallDuringHalf = Math.max(0, essential - (monthly / 2));

      if (halfMonths > 0) {
        if (shortfallDuringHalf === 0) {
          covered += halfMonths;
        } else {
          covered += Math.min(halfMonths, reserve / shortfallDuringHalf);
          reserve = Math.max(0, reserve - (halfMonths * shortfallDuringHalf));
        }
      }

      if (essential > 0) {
        covered += reserve / essential;
      }

      covered = Math.min(covered, MAX_MONTHS);
      covered = Math.round(covered * 10) / 10;

      animateTo(totalEl, previous, covered, function (n) {
        var rounded = Math.round(n * 10) / 10;
        return rounded + ' ' + plural(rounded, 'month', 'months');
      });
      previous = covered;

      var stages = [];
      if (fullMonths > 0) {
        stages.push({
          label: 'Full employer sick pay',
          detail: fullMonths + ' ' + plural(fullMonths, 'month', 'months') + ' at ' + money.format(Math.round(monthly)) + ' a month',
          tone: 'ok'
        });
      }
      if (halfMonths > 0) {
        stages.push({
          label: 'Half pay',
          detail: halfMonths + ' ' + plural(halfMonths, 'month', 'months') + ' at ' + money.format(Math.round(monthly / 2))
            + ' a month, leaving ' + money.format(Math.round(shortfallDuringHalf)) + ' a month to find',
          tone: shortfallDuringHalf > 0 ? 'warn' : 'ok'
        });
      }
      stages.push({
        label: 'After employer support ends',
        detail: 'Essential outgoings of ' + money.format(Math.round(essential))
          + ' a month with no earned income. State support, if any, is means tested and unlikely to match your pay.',
        tone: 'gap'
      });

      stages.forEach(function (stage, index) {
        var li = document.createElement('li');
        li.className = 'na-calc__stage na-calc__stage--' + stage.tone;
        li.style.setProperty('--na-stage-index', String(index));
        li.innerHTML = '<strong></strong><span></span>';
        li.querySelector('strong').textContent = stage.label;
        li.querySelector('span').textContent = stage.detail;
        timelineEl.appendChild(li);
      });

      breakdownEl.textContent = 'On these figures your income and savings would hold up for roughly '
        + covered + ' ' + plural(covered, 'month', 'months')
        + '. Employer sick pay varies by contract, so it is worth checking yours rather than assuming.';
    }

    root.addEventListener('input', recalculate);
    recalculate();
  }

  document.querySelectorAll('[data-na-calc]').forEach(function (root) {
    var kind = root.getAttribute('data-na-calc');
    if (kind === 'cover') {
      initCover(root);
    } else if (kind === 'income') {
      initIncome(root);
    }
  });
})();
