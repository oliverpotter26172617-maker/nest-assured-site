(function () {
  'use strict';

  function initialiseEnquiry(form) {
    const radios = Array.from(form.querySelectorAll('input[name="client_type"]'));
    const branches = Array.from(form.querySelectorAll('[data-branch]'));
    const message = form.querySelector('[data-na-form-message]');
    const contactPreference = form.querySelector('[data-na-contact-preference]');
    const phone = form.querySelector('[data-na-phone]');
    const adviserUnknown = form.querySelector('[data-na-adviser-unknown]');
    const adviserName = form.querySelector('#na-adviser-name');
    let formStarted = false;

    function track(event, details) {
      if (typeof window.NestAssuredTrack === 'function') {
        window.NestAssuredTrack(event, details || {});
      }
    }

    function updateBranch() {
      const selected = radios.find((radio) => radio.checked);
      const value = selected ? selected.value : '';

      branches.forEach((branch) => {
        const active = branch.dataset.branch === value;
        branch.hidden = !active;
        branch.querySelectorAll('[data-required-for]').forEach((field) => {
          field.required = active && field.dataset.requiredFor === value;
        });
      });

      if (phone && contactPreference) {
        phone.required = contactPreference.value === 'phone';
      }

      if (adviserName && adviserUnknown) {
        const existingSelected = value === 'existing';
        adviserName.disabled = existingSelected && adviserUnknown.checked;
        adviserName.required = existingSelected && !adviserUnknown.checked;
        if (adviserUnknown.checked) {
          adviserName.value = '';
        }
      }
    }

    function syncFieldErrors() {
      const summary = form.querySelector('[data-na-error-summary]');
      if (summary && form.checkValidity()) {
        summary.remove();
      }

      form.querySelectorAll('.na-field').forEach((wrap) => {
        const invalid = Array.from(wrap.querySelectorAll('input, select, textarea'))
          .find((field) => field.willValidate && !field.validity.valid);
        let errorNote = wrap.querySelector('.na-field__error');

        if (!invalid) {
          errorNote?.remove();
          return;
        }

        if (!errorNote) {
          errorNote = document.createElement('span');
          errorNote.className = 'na-field__error';
          if (invalid.id) {
            errorNote.id = `${invalid.id}-error`;
            const describedBy = (invalid.getAttribute('aria-describedby') || '').split(/\s+/).filter(Boolean);
            if (!describedBy.includes(errorNote.id)) {
              describedBy.push(errorNote.id);
              invalid.setAttribute('aria-describedby', describedBy.join(' '));
            }
          }
          wrap.appendChild(errorNote);
        }

        errorNote.textContent = invalid.validity.valueMissing
          ? 'This information is needed before the form can be sent.'
          : invalid.validationMessage;
      });
    }

    radios.forEach((radio) => radio.addEventListener('change', updateBranch));
    contactPreference?.addEventListener('change', updateBranch);
    adviserUnknown?.addEventListener('change', updateBranch);
    updateBranch();

    ['input', 'change'].forEach((type) => {
      form.addEventListener(type, () => {
        if (form.classList.contains('na-was-validated')) {
          syncFieldErrors();
        }
      });
    });

    form.addEventListener('input', () => {
      if (!formStarted) {
        formStarted = true;
        track('form_started', { form: 'enquiry' });
      }
    }, { once: true });

    if (message) {
      message.focus();
    }

    form.addEventListener('submit', (event) => {
      updateBranch();
      if (!form.checkValidity()) {
        event.preventDefault();
        form.classList.add('na-was-validated');

        let summary = form.querySelector('[data-na-error-summary]');
        if (!summary) {
          summary = document.createElement('div');
          summary.className = 'na-form__message na-form__message--error';
          summary.setAttribute('role', 'alert');
          summary.setAttribute('data-na-error-summary', '');
          summary.tabIndex = -1;
          form.prepend(summary);
        }
        summary.textContent = 'Some details are missing. Please check the highlighted fields below.';

        syncFieldErrors();
        track('form_validation_error', { form: 'enquiry' });
        form.reportValidity();
      }
    });

    if (new URLSearchParams(window.location.search).get('enquiry') === 'received') {
      track('enquiry_submitted', { form: 'enquiry' });
    }
  }

  function initialiseAssessment(root) {
    const steps = Array.from(root.querySelectorAll('[data-step]'));
    const progress = root.querySelector('.na-assessment__progress');
    const controls = root.querySelector('.na-assessment__controls');
    const next = root.querySelector('[data-assessment-next]');
    const back = root.querySelector('[data-assessment-back]');
    const result = root.querySelector('[data-assessment-result]');
    const error = root.querySelector('[data-assessment-error]');
    let current = 0;
    let started = false;

    function track(event, details) {
      if (typeof window.NestAssuredTrack === 'function') {
        window.NestAssuredTrack(event, details || {});
      }
    }

    function selectedValue(name) {
      const selected = root.querySelector(`input[name="${name}"]:checked`);
      return selected ? selected.value : '';
    }

    function renderStep() {
      steps.forEach((step, index) => {
        step.hidden = index !== current;
      });
      progress.textContent = `Question ${current + 1} of ${steps.length}`;
      back.hidden = current === 0;
      next.textContent = current === steps.length - 1 ? 'Show my starting point' : 'Next question';
      error.hidden = true;
      steps[current].removeAttribute('aria-invalid');
    }

    function showResult() {
      const concern = selectedValue('concern');
      const dependants = selectedValue('dependants');
      const workCover = selectedValue('work_cover');
      const topics = {
        family: ['Life insurance', '/life-insurance/', 'A life insurance conversation could help you understand cover designed to pay a lump sum if the insured person dies during the policy term.', 'life-insurance'],
        income: ['Income protection', '/income-protection/', 'An income protection conversation could help you understand cover designed to pay an income if illness or injury prevents you working, subject to policy terms.', 'income-protection'],
        illness: ['Critical illness cover', '/critical-illness-cover/', 'A critical illness conversation could help you understand cover designed to pay a lump sum following diagnosis of a condition covered by the policy.', 'critical-illness'],
        overall: ['Family protection', '/family-protection/', 'A broader family protection conversation could help you compare how several forms of cover may work alongside existing arrangements.', 'family-protection']
      };
      const topic = topics[concern] || topics.overall;
      let context = '';

      if (dependants === 'family') {
        context += '<p>Because family members depend on you, the adviser can include their needs in the discussion.</p>';
      } else if (dependants === 'financial') {
        context += '<p>Because another person shares financial commitments with you, the adviser can include those commitments in the discussion.</p>';
      }
      if (workCover === 'yes' || workCover === 'some' || workCover === 'unsure') {
        context += '<p>Bring any workplace benefit details you have. The adviser can help you understand how existing cover fits into the conversation.</p>';
      }

      result.innerHTML = `<p class="na-eyebrow">A useful first topic</p><h3>${topic[0]}</h3><p>${topic[2]}</p>${context}<div class="na-actions"><a class="na-button na-button--outline" href="${topic[1]}">Read the plain-English guide</a><a class="na-button" href="/enquire/?topic=${topic[3]}">Start an adviser conversation</a><button type="button" class="na-button na-button--outline" data-assessment-restart>Start again</button></div>`;
      result.hidden = false;
      result.focus();
      progress.textContent = 'Assessment complete';
      steps.forEach((step) => {
        step.hidden = true;
      });
      controls.hidden = true;
      next.hidden = true;
      back.hidden = true;
      track('assessment_completed', { topic: topic[3] });
    }

    function restart() {
      root.querySelectorAll('input[type="radio"]').forEach((radio) => {
        radio.checked = false;
      });
      result.hidden = true;
      result.innerHTML = '';
      controls.hidden = false;
      next.hidden = false;
      current = 0;
      renderStep();
      const first = steps[0].querySelector('input');
      if (first) {
        first.focus();
      }
      track('assessment_restarted', {});
    }

    result.addEventListener('click', (event) => {
      const target = event.target instanceof Element
        ? event.target.closest('[data-assessment-restart]')
        : null;
      if (target) {
        restart();
      }
    });

    next.addEventListener('click', () => {
      if (!started) {
        started = true;
        track('assessment_started', {});
      }
      const choice = steps[current].querySelector('input:checked');
      if (!choice) {
        error.hidden = false;
        steps[current].setAttribute('aria-invalid', 'true');
        const first = steps[current].querySelector('input');
        if (first) {
          first.focus();
        }
        return;
      }

      if (current < steps.length - 1) {
        current += 1;
        renderStep();
        const first = steps[current].querySelector('input');
        if (first) {
          first.focus();
        }
        return;
      }

      showResult();
    });

    back.addEventListener('click', () => {
      if (current > 0) {
        current -= 1;
        renderStep();
        const selected = steps[current].querySelector('input:checked');
        const first = steps[current].querySelector('input');
        (selected || first)?.focus();
      }
    });

    root.querySelectorAll('input[type="radio"]').forEach((radio) => {
      radio.addEventListener('change', () => {
        error.hidden = true;
        steps[current].removeAttribute('aria-invalid');
      });
    });

    renderStep();
  }

  document.querySelectorAll('[data-na-enquiry]').forEach(initialiseEnquiry);
  document.querySelectorAll('[data-na-assessment]').forEach(initialiseAssessment);
})();
