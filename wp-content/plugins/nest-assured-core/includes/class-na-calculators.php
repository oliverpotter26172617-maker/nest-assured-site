<?php
/**
 * Planning calculators.
 *
 * These are deliberately NOT quote engines. They take figures the visitor already
 * knows, do arithmetic on them, and hand back a starting figure for a conversation.
 * No premium, insurer, product or market data is involved, nothing is stored, and
 * no output is presented as a recommendation, because a tool that produced one on
 * an appointed representative's site would be advice rather than education.
 *
 * All arithmetic runs in the browser. Nothing is submitted, so no personal data
 * leaves the page.
 *
 * @package NestAssuredCore
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

final class NA_Calculators
{
    public static function init(): void
    {
        add_shortcode('nest_assured_cover_calculator', [self::class, 'cover_calculator']);
        add_shortcode('nest_assured_income_calculator', [self::class, 'income_calculator']);
        add_action('wp_enqueue_scripts', [self::class, 'enqueue']);
    }

    /**
     * True when the current page actually contains a calculator.
     */
    private static function is_calculator_page(): bool
    {
        $post = get_post();
        if (! $post instanceof WP_Post) {
            return false;
        }

        if (has_shortcode($post->post_content, 'nest_assured_cover_calculator')
            || has_shortcode($post->post_content, 'nest_assured_income_calculator')) {
            return true;
        }

        // Guides embed a calculator after the article rather than in their stored
        // content, so the shortcode is not in post_content to be found.
        return class_exists('NA_Editorial') && NA_Editorial::guide_has_calculator($post->ID);
    }

    public static function enqueue(): void
    {
        if (! self::is_calculator_page()) {
            return;
        }

        $js = NA_CORE_DIR . 'assets/calculators.js';
        wp_enqueue_script(
            'nest-assured-calculators',
            NA_CORE_URL . 'assets/calculators.js',
            [],
            is_file($js) ? (string) filemtime($js) : NA_CORE_VERSION,
            true
        );
    }

    /**
     * The standing notice shown with every calculator.
     */
    private static function notice(): string
    {
        return '<p class="na-calc__notice">This is a planning tool, not advice, a quote or a recommendation. '
            . 'It works only with the figures you enter and does not consider your health, budget, existing '
            . 'policies or circumstances. Nothing you type is sent anywhere or stored.</p>';
    }

    private static function field(string $id, string $label, string $hint = '', string $value = '', string $prefix = '&pound;'): string
    {
        $described = '' !== $hint ? ' aria-describedby="' . esc_attr($id) . '-hint"' : '';

        return '<div class="na-calc__field">'
            . '<label for="' . esc_attr($id) . '">' . esc_html($label) . '</label>'
            . '<div class="na-calc__input"><span aria-hidden="true">' . $prefix . '</span>'
            . '<input id="' . esc_attr($id) . '" type="text" inputmode="numeric" autocomplete="off" '
            . 'value="' . esc_attr($value) . '" data-na-calc-input' . $described . ' /></div>'
            . ('' !== $hint ? '<small id="' . esc_attr($id) . '-hint">' . esc_html($hint) . '</small>' : '')
            . '</div>';
    }

    /**
     * Estimates the shortfall between what a household would need and the cover it
     * already has. Every figure comes from the visitor.
     */
    public static function cover_calculator(): string
    {
        ob_start();
        ?>
        <section class="na-calc" data-na-calc="cover" aria-labelledby="na-calc-cover-title">
            <div class="na-calc__head">
                <p class="na-v2-eyebrow">Planning tool</p>
                <h2 id="na-calc-cover-title">Work out the size of the gap</h2>
                <p class="na-calc__intro">Protection conversations get easier once the numbers are on the table. Enter what you know and this adds up what would need covering, then subtracts what you already have.</p>
            </div>

            <div class="na-calc__grid">
                <div class="na-calc__inputs">
                    <fieldset>
                        <legend>What would need paying off or replacing</legend>
                        <?php
                        echo self::field('na-calc-mortgage', 'Mortgage balance outstanding', 'The amount left, not the property value.');
                        echo self::field('na-calc-debts', 'Other debts', 'Loans, credit cards, car finance.');
                        echo self::field('na-calc-income', 'Annual income to replace', 'Take-home pay is usually the more useful figure.');
                        echo self::field('na-calc-years', 'Years to replace it for', 'Until the youngest child is independent, for example.', '', '&nbsp;');
                        echo self::field('na-calc-costs', 'One-off costs to allow for', 'Funeral costs, a period without work, adapting a home.');
                        ?>
                    </fieldset>

                    <fieldset>
                        <legend>What is already in place</legend>
                        <?php
                        echo self::field('na-calc-existing', 'Existing personal cover', 'Policies you pay for yourself.');
                        echo self::field('na-calc-work', 'Death in service or employer cover', 'Often a multiple of salary. Usually ends when the job does.');
                        echo self::field('na-calc-savings', 'Savings you would use', 'Only the part you would genuinely spend on this.');
                        ?>
                    </fieldset>
                </div>

                <div class="na-calc__result" data-na-calc-result role="status" aria-live="polite">
                    <p class="na-calc__result-label">Indicative shortfall</p>
                    <p class="na-calc__result-value" data-na-calc-total>&pound;0</p>
                    <div class="na-calc__bars" data-na-calc-bars aria-hidden="true">
                        <div class="na-calc__bar na-calc__bar--need"><span data-na-calc-bar="need"></span><small>Would need covering</small></div>
                        <div class="na-calc__bar na-calc__bar--have"><span data-na-calc-bar="have"></span><small>Already in place</small></div>
                    </div>
                    <p class="na-calc__breakdown" data-na-calc-breakdown></p>
                    <a class="na-v2-btn na-v2-btn--gold" href="<?php echo esc_url(home_url('/enquire/?topic=life-insurance')); ?>">Discuss this figure with an adviser</a>
                </div>
            </div>

            <?php echo self::notice(); ?>
        </section>
        <?php
        return (string) ob_get_clean();
    }

    /**
     * Maps how long employer sick pay and savings would last, and what the drop
     * looks like afterwards. Again, entirely the visitor's own figures.
     */
    public static function income_calculator(): string
    {
        ob_start();
        ?>
        <section class="na-calc" data-na-calc="income" aria-labelledby="na-calc-income-title">
            <div class="na-calc__head">
                <p class="na-v2-eyebrow">Planning tool</p>
                <h2 id="na-calc-income-title">See where your income would drop</h2>
                <p class="na-calc__intro">Most people are covered for the first few weeks off work and far less after that. This maps your own sick pay and savings against time, so the gap becomes visible rather than theoretical.</p>
            </div>

            <div class="na-calc__grid">
                <div class="na-calc__inputs">
                    <fieldset>
                        <legend>Your income now</legend>
                        <?php
                        echo self::field('na-calc-monthly', 'Monthly take-home pay', 'After tax and deductions.');
                        echo self::field('na-calc-essential', 'Essential monthly outgoings', 'Mortgage or rent, bills, food, travel.');
                        ?>
                    </fieldset>

                    <fieldset>
                        <legend>What would support you</legend>
                        <?php
                        echo self::field('na-calc-full-months', 'Months of full employer sick pay', 'Check your contract; many people assume more than they have.', '', '&nbsp;');
                        echo self::field('na-calc-half-months', 'Months of half pay after that', 'Enter 0 if your employer does not offer this.', '', '&nbsp;');
                        echo self::field('na-calc-reserve', 'Savings you would draw on', 'The amount you would genuinely be willing to spend.');
                        ?>
                    </fieldset>
                </div>

                <div class="na-calc__result" data-na-calc-result role="status" aria-live="polite">
                    <p class="na-calc__result-label">Your income would hold up for about</p>
                    <p class="na-calc__result-value" data-na-calc-total>0 months</p>
                    <ol class="na-calc__timeline" data-na-calc-timeline></ol>
                    <p class="na-calc__breakdown" data-na-calc-breakdown></p>
                    <a class="na-v2-btn na-v2-btn--gold" href="<?php echo esc_url(home_url('/enquire/?topic=income-protection')); ?>">Discuss income protection</a>
                </div>
            </div>

            <?php echo self::notice(); ?>
        </section>
        <?php
        return (string) ob_get_clean();
    }
}
