<?php
/**
 * Question and answer data for the cover pages.
 *
 * Held here rather than inside the page content because a script tag stored in
 * post content is stripped by kses on save, and its JSON then renders as visible
 * text on the page. The visible block and the structured data are generated from
 * this one source, so they cannot drift apart.
 *
 * @package NestAssuredCore
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

final class NA_Faq
{
    public static function init(): void
    {
        add_action('wp_head', [self::class, 'render_schema'], 6);
    }

    /**
     * @return array<string, array<int, array{0:string,1:string}>>
     */
    public static function all(): array
    {
        return [
            'life-insurance' => [
                ['Does life insurance pay out for any cause of death?', 'Most policies cover death from any cause once in force, subject to their terms. There are usually exclusions, commonly around suicide in an early period, and answering the application questions accurately matters at the point of claim.'],
                ['What is the difference between level and decreasing cover?', 'Level cover keeps the same sum assured throughout the term. Decreasing cover reduces over time and is often considered alongside a repayment mortgage. Which suits a household depends on what the money would need to do.'],
                ['Should life insurance be written in trust?', 'Putting a policy in trust can affect who receives the money, how quickly it reaches them, and how it is treated for inheritance tax. Trusts have consequences of their own, so this is a point to take advice on rather than a box to tick.'],
                ['What happens if I stop paying the premiums?', 'Most protection policies have no cash value, so cover normally ends after a short grace period and nothing is returned. If affordability changes, speak to an adviser before cancelling rather than after.'],
                ['Can I get life insurance with a health condition?', 'Many conditions can be considered. An insurer may accept on ordinary terms, apply a higher premium, add an exclusion, postpone or decline, and the outcome depends on the condition, its history and the insurer.'],
                ['Do I need life insurance if I have cover through work?', 'Death in service can be valuable, but the amount is usually tied to salary and it normally ends when the job does. It is worth understanding what it would leave behind before treating the question as settled.'],
            ],
            'income-protection' => [
                ['How long does income protection pay out for?', 'It depends on the policy. Some plans limit each claim to a set period, while others can continue until the end of the policy term if you remain unable to work under its definition. The maximum payment period is one of the main things that separates otherwise similar plans.'],
                ['Is income protection the same as payment protection insurance?', 'No. Income protection is designed to replace part of your earnings when illness or injury stops you working. Payment protection was sold to cover a specific debt or credit agreement, and works differently.'],
                ['Can I get income protection if I am self-employed?', 'Self-employed people can generally be considered, though insurers assess earnings differently and usually want to see accounts or tax calculations. There is no employer sick pay behind you, which is often why the conversation matters more.'],
                ['What is a deferred period?', 'The waiting time between becoming unable to work and the policy starting to pay. A longer deferred period usually costs less, so it is normally set to line up with whatever sick pay and savings would carry you until then.'],
                ['Will income protection affect my state benefits?', 'It can. Some benefits are means tested, so an insurance payment may affect entitlement. The interaction depends on your circumstances and on current rules, so check your own position rather than assuming.'],
                ['What happens if I change job while covered?', 'A personal policy belongs to you rather than your employer, so it normally continues. Changing occupation can matter, because some definitions and premiums are tied to the work you do, so tell the insurer.'],
            ],
            'critical-illness-cover' => [
                ['What conditions does critical illness cover include?', 'Each policy lists the conditions it covers and the definition a diagnosis has to meet. The number of listed conditions on its own tells you very little, because the definitions decide whether a claim is paid.'],
                ['Does critical illness cover pay out for any cancer?', 'No. Policies define which cancers are covered and at what severity, and some earlier-stage or less advanced cancers pay a smaller amount or nothing. The wording governs, not the diagnosis alone.'],
                ['What is a survival period?', 'A short period after diagnosis that you have to survive before a claim can be paid. It is set by the policy and is one of the terms worth checking before comparing plans on price.'],
                ['Can I add children\'s cover?', 'Many policies include or offer cover for children, but the conditions, limits and age ranges vary widely, and it is not available on every plan. Check what is included rather than assuming.'],
                ['Is critical illness cover the same as life insurance?', 'No. Life cover pays on death. Critical illness cover pays on diagnosis of a condition the policy covers, while you are alive. Some people hold both, sometimes on the same policy.'],
                ['Do I still need it if I have cover through work?', 'Workplace benefits can be valuable, but the amount, the conditions and the fact they usually end when the job does all need understanding before you decide the question is settled.'],
            ],
            'family-protection' => [
                ['How much life insurance does a family need?', 'There is no universal figure. The useful approach is to work out what would need paying off, what income would need replacing and for how long, then subtract the cover you already hold. The calculators on this site do that arithmetic.'],
                ['Should cover be joint or two single policies?', 'They behave differently. A joint policy pays once and ends, leaving the survivor without cover. Two single policies each pay separately. Which suits a household depends on circumstances, and it is a conversation rather than a rule.'],
                ['What happens to cover if we separate?', 'A joint policy cannot simply be split in two. Some plans include a separation option allowing two single policies without further medical evidence, but it is not universal and it has to be checked in the wording.'],
                ['Do stay-at-home parents need cover?', 'The question is what it would cost to replace what they do. Childcare, care for a relative and running a household all have a real cost if they have to be bought in, so a parent with no salary can still leave a substantial gap.'],
                ['Does life insurance pay out for any cause of death?', 'Most policies cover death from any cause once in force, subject to their terms. There are usually exclusions, commonly around suicide in the early period, and answering the application questions accurately matters at claim.'],
                ['What is family income benefit?', 'Cover that pays a regular income for the rest of the policy term rather than a single lump sum. Some households find a monthly figure easier to plan around than a large one-off amount.'],
            ],
            'private-medical-insurance' => [
                ['Does private medical insurance replace the NHS?', 'No. Having a policy does not remove your right to NHS care. It is designed to provide another route for eligible treatment, and what it covers depends on the plan.'],
                ['Are pre-existing conditions covered?', 'Usually not, at least at outset. How they are handled depends on whether the policy is fully underwritten or written on a moratorium basis, and the wording sets out how and whether a condition can later be reconsidered.'],
                ['What is the difference between acute and chronic?', 'Policies are generally built around acute conditions that respond to treatment. Chronic conditions, which are ongoing and managed rather than cured, are usually excluded or limited. Each policy defines both terms.'],
                ['Can I choose my own hospital or consultant?', 'It depends on the plan. Some use a fixed hospital list, others are wider, and some route you through a guided or open referral process. This is one of the main levers on cost.'],
                ['What is a six-week option?', 'A plan feature where private treatment is used only if the NHS wait would be longer than six weeks. It usually reduces the premium and generally applies to admitted treatment rather than every benefit.'],
                ['What happens to cover if I leave my employer\'s scheme?', 'Some schemes offer continuation terms allowing a personal policy without full underwriting, usually within a short window. It varies by scheme and insurer, so ask the administrator before your last day.'],
            ],
            'business-protection' => [
                ['Who owns a key person policy and who receives the payment?', 'Key person cover is usually owned by and paid to the business, because it is designed to protect the company against the loss of someone it depends on. Ownership needs to match the purpose of the cover.'],
                ['What is shareholder protection?', 'Cover arranged so the remaining owners have funds to buy an affected owner\'s share, normally alongside an appropriately drafted agreement. The cover and the agreement have to work together.'],
                ['Can a relevant life policy include critical illness?', 'No. A relevant life policy can only provide a lump sum on death, with terminal illness generally accepted as an acceleration of that benefit. Critical illness cover has to be arranged separately.'],
                ['Is business protection tax deductible?', 'It depends on the arrangement, who owns the policy, who benefits and the purpose of the cover. Tax treatment depends on individual circumstances and current rules, both of which can change, so take advice from the company accountant.'],
                ['Does a sole trader need business protection?', 'The question is dependency rather than size. If revenue, borrowing or an ownership position would be materially affected by losing one person, the conversation is relevant however small the business.'],
                ['What is executive income protection?', 'Cover designed to help a business keep paying an employee who cannot work through illness or injury, subject to the policy terms. What it can include, such as employer costs, varies by provider.'],
            ],
            'general-insurance' => [
                ['What is the difference between buildings and contents insurance?', 'Buildings cover is designed around the structure and permanent fixtures. Contents cover is designed around the belongings you would normally take with you if you moved.'],
                ['How much buildings cover do I need?', 'It is based on the cost of rebuilding the property, which is a different figure from what the property is worth on the market. Insuring against the wrong one is a common way for a policy to fall short.'],
                ['What is underinsurance?', 'Insuring for less than the true value. Where an average condition applies, a settlement can be reduced in proportion to the shortfall, so a claim may pay less than expected even if it is well below the sum insured.'],
                ['Do I have to have buildings insurance?', 'A mortgage lender will normally require suitable buildings cover as a condition of the loan. Contents cover is usually optional. Leasehold arrangements may already include buildings cover, so check the documents.'],
                ['Are my valuables covered automatically?', 'Not necessarily. Policies usually apply a single item limit and a total limit for valuables, and higher value items often need to be specified. Belongings away from home may need separate cover.'],
                ['When do I need cover from when buying a home?', 'Confirm the exact date with your conveyancer and your lender. Contracts vary on when risk passes, and many lenders want cover in force from exchange rather than from moving day.'],
            ],
        ];
    }

    /**
     * @return array<int, array{0:string,1:string}>
     */
    public static function for_slug(string $slug): array
    {
        $all = self::all();

        return $all[$slug] ?? [];
    }

    public static function render_schema(): void
    {
        if (! is_singular('page')) {
            return;
        }

        $pairs = self::for_slug((string) get_post_field('post_name', get_queried_object_id()));
        if ([] === $pairs) {
            return;
        }

        $questions = [];
        foreach ($pairs as [$question, $answer]) {
            $questions[] = [
                '@type'          => 'Question',
                'name'           => $question,
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $answer],
            ];
        }

        echo '<script type="application/ld+json">'
            . wp_json_encode(
                ['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $questions],
                JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
            )
            . '</script>' . "\n";
    }
}
