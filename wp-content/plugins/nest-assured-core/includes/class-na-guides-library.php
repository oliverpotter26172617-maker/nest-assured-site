<?php
/**
 * Guide library, second series.
 *
 * Twenty long-form guides written around the questions people actually search
 * for. Deliberately free of figures: no premium, payout, percentage or market
 * statistic appears anywhere, because none of it could be substantiated, and an
 * unsubstantiated number in a financial promotion is the expensive kind of
 * mistake. Anything that varies is described as varying.
 *
 * @package NestAssuredCore
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

final class NA_Guides_Library
{
    /**
     * @return array<int, array{0:string,1:string,2:string}>
     */
    public static function guides(): array
    {
        return [
            ['Children\'s critical illness cover', 'critical-illness-cover-for-children', self::guide_critical_illness_cover_for_children()],
            ['Death in service and personal life cover', 'death-in-service-and-life-insurance', self::guide_death_in_service_and_life_insurance()],
            ['Decreasing or level term life insurance?', 'decreasing-vs-level-term-life-insurance', self::guide_decreasing_vs_level_term_life_insurance()],
            ['Family income benefit explained', 'family-income-benefit-explained', self::guide_family_income_benefit_explained()],
            ['How much income protection can you get?', 'how-much-income-protection-can-i-get', self::guide_how_much_income_protection_can_i_get()],
            ['How much life insurance do you need?', 'how-much-life-insurance-do-i-need', self::guide_how_much_life_insurance_do_i_need()],
            ['Joint or single life insurance policies?', 'joint-vs-single-life-insurance', self::guide_joint_vs_single_life_insurance()],
            ['Life insurance and inheritance tax', 'life-insurance-and-inheritance-tax', self::guide_life_insurance_and_inheritance_tax()],
            ['Life insurance and your mortgage', 'life-insurance-and-mortgages', self::guide_life_insurance_and_mortgages()],
            ['Life insurance in your fifties and sixties | Nest Assured', 'life-insurance-for-over-50s', self::guide_life_insurance_for_over_50s()],
            ['What happens during medical underwriting', 'life-insurance-medical-underwriting', self::guide_life_insurance_medical_underwriting()],
            ['Life insurance with a pre-existing condition', 'life-insurance-pre-existing-conditions', self::guide_life_insurance_pre_existing_conditions()],
            ['What happens if you miss a premium | Nest Assured', 'missed-premium-payments', self::guide_missed_premium_payments()],
            ['Own occupation or any occupation cover?', 'own-occupation-vs-any-occupation', self::guide_own_occupation_vs_any_occupation()],
            ['Protection when you work for yourself | Nest Assured', 'protection-when-self-employed', self::guide_protection_when_self_employed()],
            ['Smoking, vaping and protection insurance', 'smoking-vaping-and-life-insurance', self::guide_smoking_vaping_and_life_insurance()],
            ['Switching or cancelling a protection policy | Nest Assured', 'switching-or-cancelling-protection', self::guide_switching_or_cancelling_protection()],
            ['Waiver of premium explained', 'waiver-of-premium-explained', self::guide_waiver_of_premium_explained()],
            ['Whole of life insurance explained | Nest Assured', 'whole-of-life-insurance-explained', self::guide_whole_of_life_insurance_explained()],
            ['Putting life insurance in trust, step by step', 'writing-life-insurance-in-trust', self::guide_writing_life_insurance_in_trust()],
        ];
    }

    /**
     * Slug to metadata, for page titles, meta descriptions and card eyebrows.
     *
     * @return array<string, array{title:string,description:string,eyebrow:string}>
     */
    public static function meta(): array
    {
        return [
            'critical-illness-cover-for-children' => ['title' => 'Children\'s critical illness cover', 'description' => 'How children\'s critical illness cover works in the UK, what it typically includes, the limits and exclusions to check, and where it commonly falls short.', 'eyebrow' => 'Critical illness cover'],
            'death-in-service-and-life-insurance' => ['title' => 'Death in service and personal life cover', 'description' => 'How employer death in service cover works, why it ends when the job does, and how to count it alongside personal life cover without leaving a gap.', 'eyebrow' => 'Life cover'],
            'decreasing-vs-level-term-life-insurance' => ['title' => 'Decreasing or level term life insurance?', 'description' => 'How decreasing and level term life cover differ, why decreasing usually costs less, how the reduction is worked out, and when each shape stops fitting.', 'eyebrow' => 'Life insurance'],
            'family-income-benefit-explained' => ['title' => 'Family income benefit explained', 'description' => 'Family income benefit pays a regular income rather than a lump sum. How the term shapes the payout, why it costs less, and what to check in the wording.', 'eyebrow' => 'Life insurance'],
            'how-much-income-protection-can-i-get' => ['title' => 'How much income protection can you get?', 'description' => 'How UK insurers cap income protection benefit against your earnings, what counts as income, what is deducted, and how to work out the gap you need.', 'eyebrow' => 'Income protection'],
            'how-much-life-insurance-do-i-need' => ['title' => 'How much life insurance do you need?', 'description' => 'How to size a UK life insurance policy: the debts to clear, the income to replace, the one-off costs, and the cover you may already hold at work.', 'eyebrow' => 'Life insurance'],
            'joint-vs-single-life-insurance' => ['title' => 'Joint or single life insurance policies?', 'description' => 'Joint life cover pays once and ends. Two single policies pay twice. What that means for the survivor, for separation, and for how much each of you holds.', 'eyebrow' => 'Life insurance'],
            'life-insurance-and-inheritance-tax' => ['title' => 'Life insurance and inheritance tax', 'description' => 'How a life policy can add to your estate or help pay an inheritance tax bill, why trusts matter, and where legal or tax advice is needed alongside.', 'eyebrow' => 'Estate planning'],
            'life-insurance-and-mortgages' => ['title' => 'Life insurance and your mortgage', 'description' => 'Is life cover compulsory with a UK mortgage? What happens to the loan if a borrower dies, how property ownership affects it, and when to arrange cover.', 'eyebrow' => 'Mortgage protection'],
            'life-insurance-for-over-50s' => ['title' => 'Life insurance in your fifties and sixties | Nest Assured', 'description' => 'What changes about life cover in your fifties and sixties: underwriting, existing policies, term limits, over-50s plans, and providing for family.', 'eyebrow' => 'Life stages'],
            'life-insurance-medical-underwriting' => ['title' => 'What happens during medical underwriting', 'description' => 'What medical underwriting involves for UK protection cover: application questions, GP reports, medical screenings, and the decisions insurers can make.', 'eyebrow' => 'Health and underwriting'],
            'life-insurance-pre-existing-conditions' => ['title' => 'Life insurance with a pre-existing condition', 'description' => 'How UK life insurance underwriting handles a pre-existing condition, the terms insurers can offer, and why full, accurate answers matter at application.', 'eyebrow' => 'Health and underwriting'],
            'missed-premium-payments' => ['title' => 'What happens if you miss a premium | Nest Assured', 'description' => 'What happens when a protection premium fails: grace periods, arrears, lapse, reinstatement, and the options to discuss before cover is lost for good.', 'eyebrow' => 'Keeping cover'],
            'own-occupation-vs-any-occupation' => ['title' => 'Own occupation or any occupation cover?', 'description' => 'Own occupation, suited occupation, any occupation and activity based tests explained, why insurers offer different ones, and where claims come unstuck.', 'eyebrow' => 'Income protection'],
            'protection-when-self-employed' => ['title' => 'Protection when you work for yourself | Nest Assured', 'description' => 'Self-employed protection in the UK: income protection, how insurers define your earnings, business cover, and the gaps that catch sole traders out.', 'eyebrow' => 'Working for yourself'],
            'smoking-vaping-and-life-insurance' => ['title' => 'Smoking, vaping and protection insurance', 'description' => 'How UK insurers treat smoking, vaping and nicotine use on life, critical illness and income protection, and what to do after you have given up.', 'eyebrow' => 'Lifestyle and cover'],
            'switching-or-cancelling-protection' => ['title' => 'Switching or cancelling a protection policy | Nest Assured', 'description' => 'Why you should never cancel existing cover before a replacement is in force, what you may give up, and safer alternatives to cancelling a UK policy.', 'eyebrow' => 'Existing policies'],
            'waiver-of-premium-explained' => ['title' => 'Waiver of premium explained', 'description' => 'What waiver of premium does on a UK protection policy, how deferred periods and incapacity definitions work, and the gaps people most often miss.', 'eyebrow' => 'Policy features'],
            'whole-of-life-insurance-explained' => ['title' => 'Whole of life insurance explained | Nest Assured', 'description' => 'How whole of life cover works in the UK: guaranteed and reviewable premiums, premium reviews, trusts, and what commonly goes wrong with these plans.', 'eyebrow' => 'Types of cover'],
            'writing-life-insurance-in-trust' => ['title' => 'Putting life insurance in trust, step by step', 'description' => 'A step by step guide to putting a UK life policy in trust: trust types, choosing trustees, signing and witnessing, and the mistakes that undo it all.', 'eyebrow' => 'Estate planning'],
        ];
    }


    /**
     * Every guide on the site, first and second series, in one place.
     *
     * The hub used to hand-maintain its own copy of each card's title and
     * description, which is why the home page, the hub and the article had
     * already drifted to three different titles for the same guide.
     *
     * @return array<string, array{title:string,eyebrow:string,group:string}>
     */
    public static function catalogue(): array
    {
        $legacy = [
            'life-insurance-vs-critical-illness-cover'  => ['Life insurance or critical illness cover?', 'Compare cover', 'personal'],
            'income-protection-and-sick-pay'            => ['Income protection and employer sick pay', 'Protecting income', 'personal'],
            'income-protection-for-self-employed'       => ['Income protection for self-employed people', 'Self-employed', 'personal'],
            'life-insurance-and-trusts'                 => ['Life insurance and trusts', 'Ownership', 'personal'],
            'when-to-review-protection-insurance'       => ['When should you review protection insurance?', 'Reviews', 'support'],
            'choosing-private-medical-insurance'        => ['Choosing private medical insurance', 'Choosing cover', 'health'],
            'leaving-company-private-medical-insurance' => ['Leaving a company private medical scheme', 'Changing jobs', 'health'],
            'types-of-business-protection'              => ['Types of business protection explained', 'Explainer', 'business'],
            'relevant-life-vs-key-person-cover'         => ['Relevant life cover or key person cover?', 'Compare', 'business'],
            'buildings-and-contents-insurance'          => ['Buildings and contents insurance explained', 'Explainer', 'home'],
            'making-a-protection-insurance-claim'       => ['Making a protection insurance claim', 'Practical', 'support'],
            'insurance-jargon-buster'                   => ['Insurance jargon buster', 'Glossary', 'support'],
            'preparing-for-protection-appointment'      => ['Preparing for a protection appointment', 'Checklist', 'support'],
        ];

        // Which section of the hub each second-series guide belongs in.
        $groups = [
            'how-much-life-insurance-do-i-need'       => 'personal',
            'decreasing-vs-level-term-life-insurance' => 'personal',
            'joint-vs-single-life-insurance'          => 'personal',
            'family-income-benefit-explained'         => 'personal',
            'life-insurance-and-mortgages'            => 'personal',
            'whole-of-life-insurance-explained'       => 'personal',
            'life-insurance-for-over-50s'             => 'personal',
            'how-much-income-protection-can-i-get'    => 'personal',
            'own-occupation-vs-any-occupation'        => 'personal',
            'death-in-service-and-life-insurance'     => 'personal',
            'protection-when-self-employed'           => 'personal',
            'life-insurance-pre-existing-conditions'  => 'health',
            'life-insurance-medical-underwriting'     => 'health',
            'smoking-vaping-and-life-insurance'       => 'health',
            'critical-illness-cover-for-children'     => 'health',
            'waiver-of-premium-explained'             => 'support',
            'switching-or-cancelling-protection'      => 'support',
            'missed-premium-payments'                 => 'support',
            'life-insurance-and-inheritance-tax'      => 'estate',
            'writing-life-insurance-in-trust'         => 'estate',
        ];

        $catalogue = [];
        foreach ($legacy as $slug => $row) {
            $catalogue[$slug] = ['title' => $row[0], 'eyebrow' => $row[1], 'group' => $row[2]];
        }

        foreach (self::meta() as $slug => $meta) {
            $catalogue[$slug] = [
                'title'   => $meta['title'],
                'eyebrow' => $meta['eyebrow'],
                'group'   => $groups[$slug] ?? 'support',
            ];
        }

        return $catalogue;
    }

    /**
     * @return array<string, string>
     */
    public static function groups(): array
    {
        return [
            'personal' => 'Personal and family protection',
            'health'   => 'Health and underwriting',
            'business' => 'Business protection',
            'home'     => 'Home and general insurance',
            'estate'   => 'Trusts and estate',
            'support'  => 'Keeping cover working',
        ];
    }

    private static function html(string $content): string
    {
        return "<!-- wp:html -->\n" . trim($content) . "\n<!-- /wp:html -->";
    }

    public static function guide_critical_illness_cover_for_children(): string
    {
        return self::html('<nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a> <span aria-hidden="true">/</span> <a href="/guides/">Guides</a> <span aria-hidden="true">/</span> <span>Children\'s critical illness</span></nav>

<p class="na-eyebrow">Critical illness cover</p>

<p class="na-lede">Most UK critical illness policies include some cover for the policyholder\'s children. What it covers, how much it pays and how long it lasts vary considerably between insurers, and the detail is where the value sits.</p>

<h2>What children\'s critical illness cover is</h2>

<p>Children\'s critical illness cover is usually a benefit sitting inside an adult critical illness policy rather than a standalone product. If the adult who holds the policy has a child who is diagnosed with one of the conditions the policy covers, and that diagnosis meets the policy definition, a lump sum is paid.</p>

<p>Some insurers include it automatically as part of the plan. Others offer it as an optional extra at additional cost, sometimes with a wider range of covered conditions than the included version. A small number of insurers offer children\'s cover on a standalone basis. Which model applies depends entirely on the insurer and the plan.</p>

<p>It is worth being clear about what the money is for. It does not undo a diagnosis and it is not a substitute for NHS treatment. What it does is remove financial pressure at a point when parents typically need to be somewhere other than work: reducing hours, travelling to a specialist hospital, paying for accommodation, adapting a home, or simply covering the mortgage while normal life stops.</p>

<h2>Which children are covered</h2>

<p>Definitions differ, but policies commonly extend to natural children, legally adopted children, stepchildren and children for whom the policyholder is a legal guardian. Some wordings include children born after the policy starts automatically, and some require them to be added.</p>

<p>Cover generally begins a set period after birth rather than from birth itself, and it ends at an upper age defined in the policy. Some insurers extend that upper age where the child remains in full-time education. Both the starting point and the ending age are set by the insurer, so they are worth checking rather than assuming.</p>

<h2>What is actually covered</h2>

<p>Two different things sit under the same heading, and confusing them is a common source of disappointment at claim.</p>

<div class="na-callout-grid">
<div class="na-callout"><h3>The adult conditions list</h3><p>Many policies cover children for the same conditions as the adult, using the same definitions. Those definitions were written with adults in mind and some map awkwardly onto childhood illness.</p></div>
<div class="na-callout"><h3>Child-specific conditions</h3><p>Better wordings add conditions relevant to children, which can include certain congenital conditions, cerebral palsy, type 1 diabetes and specified childhood cancers. Availability varies widely.</p></div>
<div class="na-callout"><h3>Additional benefits</h3><p>Plans often add practical benefits such as a child funeral benefit, hospitalisation payments, family accommodation costs and access to second medical opinion or support services.</p></div>
<div class="na-callout"><h3>Definitions and severity</h3><p>Every condition has a written definition and often a severity threshold. A diagnosis in everyday language does not automatically meet the policy definition of that condition.</p></div>
</div>

<p>Policies also apply a survival period, meaning the child must survive for a defined number of days after diagnosis for the benefit to be payable. The length of that period is set by the insurer.</p>

<h2>How much it pays</h2>

<p>Children\'s benefit is normally a proportion of the adult sum assured, subject to a maximum amount specified by the insurer. That means the payment is capped and does not simply mirror the parent\'s cover. Where the optional or enhanced version is bought, the amounts are often higher.</p>

<p>Because the cap is set by the insurer rather than by you, comparing children\'s cover on headline inclusion alone is misleading. Two plans that both say they include children\'s cover can behave very differently at claim.</p>

<h2>Exclusions people do not expect</h2>

<p>The most significant exclusion relates to conditions that existed, or showed symptoms, before the cover started or before the child was added. Congenital conditions and conditions diagnosed within a defined period after birth are also commonly excluded or restricted. The precise treatment varies by insurer, and this is one of the areas where wordings differ most.</p>

<p>Other points to check include whether the benefit is payable more than once across different children, whether a claim for one child affects cover for the others, and whether the general exclusions on the adult policy also apply to the children\'s benefit.</p>

<h2>What a claim does to the main policy</h2>

<p>On most plans a children\'s claim does not end the adult policy, and the adult cover continues afterwards. However, some wordings reduce the adult sum assured by the amount paid, sometimes with reinstatement after a period, and some restrict further children\'s claims for the same condition. This is a specific question worth asking about any plan, because the answer changes what you are left with.</p>

<h2>Where children\'s cover is not the answer</h2>

<p>Children\'s critical illness cover pays a lump sum on diagnosis. It does not replace an income. If the financial risk you are most concerned about is a parent needing to stop or reduce work for a long period, that is what income protection is designed to address, and the two products solve different problems.</p>

<p>It is also worth remembering that the largest financial risk in most households is still to the adults, because the household depends on their earnings. Children\'s cover is a valuable addition to a protection arrangement rather than the foundation of one. Whether it should be prioritised depends on your circumstances, your budget and what is already in place.</p>

<h2>Practical points that catch people out</h2>

<ul class="na-checklist">
<li>Assuming all critical illness policies include children\'s cover. Some charge for it and some exclude it.</li>
<li>Assuming the cover follows you when you switch policies. It does not, and a new policy means new terms and new exclusions.</li>
<li>Assuming a diagnosis automatically triggers a payment. The policy definition and severity threshold govern the claim.</li>
<li>Both parents holding separate policies without checking how each insurer treats a claim for the same child.</li>
<li>Not knowing the upper age limit, and discovering it after a child has passed it.</li>
<li>Overlooking the additional benefits, such as hospitalisation or accommodation payments, which may be claimable in situations where the main lump sum is not.</li>
<li>Not telling the insurer about a new child where the wording requires them to be added.</li>
</ul>

<h2>Making a claim</h2>

<p>Claims are made by the policyholder, not the child, and the benefit is normally paid to the policyholder. Insurers usually ask for medical evidence from the treating consultant, so a claim can take time to assess while records are gathered. Tell the insurer early rather than waiting until treatment concludes, and keep a record of the condition names and dates as given by the hospital, since those are what the definitions are tested against.</p>

<p>Where a policy is written in trust, check how the trust deals with children\'s benefit, as arrangements differ and the wording determines who receives the payment.</p>

<h2>What an adviser will want to understand</h2>

<ul class="na-checklist">
<li>How many children you have, their ages, and whether any are stepchildren or under legal guardianship.</li>
<li>Whether you are planning to have more children during the term of the policy.</li>
<li>Any existing health conditions affecting a child, as these may be excluded.</li>
<li>What critical illness or life cover you already hold, and whether it already includes children\'s benefit.</li>
<li>Whether both parents hold cover, and how the household would function financially if one had to stop working.</li>
<li>Your mortgage, rent and essential monthly commitments, and how long they could be met without your usual income.</li>
<li>Any employer benefits such as sick pay, death in service or private medical cover.</li>
<li>Your budget, and how you would want to balance children\'s cover against the adult cover in place.</li>
<li>Whether existing policies are written in trust.</li>
</ul>

<p class="na-disclaimer">This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.</p>');
    }

    public static function guide_death_in_service_and_life_insurance(): string
    {
        return self::html('<nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a> <span aria-hidden="true">/</span> <a href="/guides/">Guides</a> <span aria-hidden="true">/</span> <span>Death in service</span></nav>

<p class="na-eyebrow">Life cover</p>

<p class="na-lede">Death in service is a valuable employee benefit, but it belongs to the job rather than to you. This guide explains how it works, how it differs from a personal life policy, and how to count it properly when you look at your family\'s position.</p>

<h2>What death in service actually is</h2>

<p>Death in service is life cover arranged and paid for by an employer under a group life scheme. If you die while employed, a lump sum is paid, usually expressed as a multiple of your salary. Some schemes also provide a dependant\'s pension instead of, or alongside, the lump sum.</p>

<p>You are not the policyholder. The employer arranges the scheme with an insurer, decides the level of cover, pays the premiums and can change or withdraw the arrangement. Your entitlement comes from your employment, and it typically requires no medical underwriting, which is one of its real advantages: people who would struggle to obtain personal cover on standard terms are usually still covered under a group scheme, sometimes subject to a limit above which individual underwriting applies.</p>

<h2>How the money is paid out</h2>

<p>Most registered group life schemes are written under a discretionary trust. That means the trustees, not you, decide who receives the money, guided by your expression of wish or nomination form. The practical consequences are worth understanding.</p>

<ul>
<li>Payment does not normally have to wait for probate, so money can reach the family relatively quickly.</li>
<li>Because the trustees hold discretion, the lump sum does not usually form part of your estate, which has inheritance tax implications covered in our separate guide on that subject.</li>
<li>Your nomination form guides the trustees but generally does not bind them. If your circumstances have changed, an out-of-date form can point the trustees in entirely the wrong direction.</li>
</ul>

<p>The nomination form is the single most neglected piece of paperwork in employee benefits. People complete it in their first week, then marry, separate, have children or buy a house with a new partner, and never update it. Reviewing it after any significant life event costs nothing.</p>

<h2>Where death in service and personal cover differ</h2>

<div class="na-callout-grid">
<div class="na-callout"><h3>Who controls it</h3><p>An employer decides the level of death in service cover and can reduce or remove the scheme. A personal policy is a contract between you and the insurer, and only you can change or cancel it.</p></div>
<div class="na-callout"><h3>How long it lasts</h3><p>Death in service ends when the employment ends, including redundancy, resignation, retirement and often long-term sickness beyond a set point. A personal policy runs for the term you chose, regardless of where you work.</p></div>
<div class="na-callout"><h3>What it is linked to</h3><p>Group cover is usually a multiple of salary, so it falls if you go part time, take a pay cut or move to a lower-paid role. Personal cover is a sum you set, and it is not linked to your earnings.</p></div>
<div class="na-callout"><h3>What it covers</h3><p>Death in service generally covers death, and sometimes terminal illness. It does not normally pay out on a critical illness diagnosis, and it is not the same as group income protection.</p></div>
</div>

<h2>The gap that opens when you leave</h2>

<p>The most common problem with death in service is not the cover itself, it is what happens when it stops. Cover ends on the day your employment ends, and there is no run-off period unless the scheme rules say otherwise.</p>

<p>That is a difficult moment to be arranging replacement cover. You will be older than when you last looked at the subject, and your health may have changed. A personal policy is priced on your age and health at the point you apply, so a condition diagnosed during those employed years can mean higher premiums, exclusions, or in some cases no offer at all. The cover you were relying on disappears at exactly the point your ability to replace it may have reduced.</p>

<p>Some group schemes include a continuation option, allowing you to take out an individual policy with the same insurer when you leave without full medical underwriting. Where it exists, it is usually time limited, often to a short window after leaving, and the premium will be set on individual rates. Whether your scheme offers one, and on what terms, depends on the scheme rules, so it is worth asking your employer rather than assuming.</p>

<h2>Counting it properly in a needs analysis</h2>

<p>Death in service is real cover and should not be ignored. Equally, treating it as permanent leads people to under-insure. A more careful approach separates the two questions.</p>

<ol>
<li>Work out the total need: what would have to be paid off, replaced or funded if you died. Typically the mortgage, other debts, funeral costs, an income for dependants, and any specific goals such as education costs.</li>
<li>Note what death in service would provide today, based on your current salary and the current multiple.</li>
<li>Then ask what happens to that figure if you change employer, reduce your hours, or retire before the need ends. If the mortgage has twenty years to run and the cover depends on staying with one employer for twenty years, the two are not really matched.</li>
</ol>

<p>Because the group cover is salary-linked, it also moves in the opposite direction to need at some points in life. Someone dropping to part-time hours after having children often has a higher protection need and a lower group benefit at the same time.</p>

<h2>Tax and allowance considerations</h2>

<p>Lump sums from registered group life schemes are tested against the relevant lump sum allowances that apply to death benefits, and the rules in this area have changed in recent years. Some employers use excepted group life arrangements, which sit outside the registered pension framework and are treated differently again.</p>

<p>The point for most people is simply this: if you have significant pension death benefits as well as death in service, the combined position is worth checking rather than assumed. Tax treatment depends on individual circumstances and can change, and specialist tax advice may be needed alongside insurance advice.</p>

<h2>What commonly goes wrong</h2>

<ul>
<li>Cancelling personal life cover after starting a job with generous death in service, then leaving that job several years later in worse health.</li>
<li>A nomination form naming a former partner, or naming no one at all.</li>
<li>Assuming the benefit covers critical illness or long-term sickness. Those are separate products.</li>
<li>Counting a salary multiple based on total package when the scheme defines salary as basic pay only.</li>
<li>Overlooking that the cover usually ends at a scheme age limit even if you carry on working.</li>
<li>Contractors and directors of their own companies assuming they have death in service when nothing has been set up. Relevant life cover is one route businesses use to provide individual death benefit for an employee or director, and the tax treatment of that depends on individual and business circumstances.</li>
</ul>

<h2>What an adviser will want to understand</h2>

<ul class="na-checklist">
<li>Whether you have death in service, the salary multiple, and how the scheme defines salary.</li>
<li>Whether the benefit is a lump sum, a dependant\'s pension, or both.</li>
<li>Whether you have completed a nomination or expression of wish form, and when you last reviewed it.</li>
<li>The scheme\'s end age and any conditions that stop cover during long-term sickness.</li>
<li>Whether a continuation option exists and what window applies.</li>
<li>How secure and long-term you expect your current employment to be.</li>
<li>Your mortgage balance, remaining term and any other debts.</li>
<li>Who depends on your income, and for how many more years.</li>
<li>Any personal life cover you already hold, including cover attached to a mortgage.</li>
<li>Pension death benefits and any nominations attached to them.</li>
<li>Your health now, since terms for personal cover are set on current circumstances.</li>
</ul>

<p class="na-disclaimer">This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.</p>');
    }

    public static function guide_decreasing_vs_level_term_life_insurance(): string
    {
        return self::html('<nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a> <span aria-hidden="true">/</span> <a href="/guides/">Guides</a> <span aria-hidden="true">/</span> <span>Decreasing or level term</span></nav>

<p class="na-eyebrow">Life insurance</p>

<p class="na-lede">Both pay out if you die during the term. The difference is whether the amount stays the same or falls over time, and that difference only matters once you know what the money is for.</p>

<h2>The two shapes of cover</h2>

<p>Level term life insurance pays a fixed sum assured throughout the term. Take out cover for a set amount over a set number of years, and if a valid claim is made in year two or year twenty, the amount payable is the same.</p>

<p>Decreasing term life insurance starts at a chosen amount and reduces as the term runs on. It is most often set up to shadow a repayment mortgage, so the cover falls at roughly the same pace as the outstanding balance. It is sometimes called mortgage life insurance or mortgage protection, though those labels are marketing terms rather than a distinct product category.</p>

<p>Both are term assurance. Neither builds a cash value. If you survive the term, or if you cancel, nothing is paid back. That is not a flaw in the design, it is why term cover is generally the cheaper way to buy a given amount of life cover for a defined period.</p>

<h2>Why decreasing cover is usually cheaper</h2>

<p>The insurer\'s exposure falls as the term progresses, so for the same starting sum assured and the same term, decreasing cover typically costs less each month than level cover. How much less depends on the insurer, the term, your age, your health and how the reduction is calculated, so it is not a fixed relationship and should be checked on actual quotes rather than assumed.</p>

<p>That price difference is the entire attraction, and it is a genuine one. Lower premiums are easier to sustain, and cover that stays in force is worth more than cover that lapses.</p>

<div class="na-callout-grid">
  <div class="na-callout">
    <h3>Level term</h3>
    <p>Fixed sum assured for the whole term. Suits a need that does not shrink, such as replacing income for dependent children or leaving a legacy.</p>
  </div>
  <div class="na-callout">
    <h3>Decreasing term</h3>
    <p>Sum assured falls over the term. Designed to sit alongside a debt that is being repaid, most commonly a capital and interest mortgage.</p>
  </div>
  <div class="na-callout">
    <h3>Increasing cover</h3>
    <p>A third option on some policies, where the sum assured rises, often in line with an index. Premiums usually rise too. Aimed at protecting against inflation over a long term.</p>
  </div>
</div>

<h2>The detail that catches people out: how the decrease is calculated</h2>

<p>This is the single most important thing to check, and it rarely appears on a comparison table.</p>

<p>A decreasing term policy does not track your actual mortgage balance. It reduces on its own schedule, which is normally based on an assumed interest rate written into the policy. If your mortgage interest rate is at or below that assumed rate, the cover typically stays at or above the balance. If your mortgage rate rises above the assumed rate, your balance can reduce more slowly than the cover does, and a gap can open up.</p>

<p>Because the assumed rate, the reduction pattern and any initial level period all vary between insurers, this is a policy wording question rather than a general rule. It is worth asking directly what rate the reduction assumes and how the sum assured behaves in the early years.</p>

<h2>Where decreasing cover stops fitting</h2>

<p>The logic of decreasing cover depends on the debt behaving as expected. Several common situations break that assumption:</p>

<ul>
  <li>Interest-only mortgages. The capital does not reduce, so cover that reduces will fall away from a balance that does not move.</li>
  <li>Part and part mortgages. Only the repayment element amortises, so a straight decreasing sum assured may not follow the total.</li>
  <li>Remortgaging for more. Taking a further advance, consolidating debt or borrowing for an extension raises the balance while the policy keeps stepping down.</li>
  <li>Extending the term. Stretching a mortgage to reduce monthly payments slows the capital repayment, while the policy carries on reducing to its original schedule.</li>
  <li>Offset or flexible arrangements. Drawdown facilities and payment holidays change the balance in ways the policy schedule does not see.</li>
  <li>Needs beyond the mortgage. If part of the sum is meant to support a family rather than clear a loan, that part of the need does not shrink just because the loan does.</li>
</ul>

<h2>Where level cover earns its extra cost</h2>

<p>Level term keeps its value in situations where the underlying need is flat or growing. Replacing a lost income for a household with young children is the obvious case, since the need is arguably at its highest in the later years when university, driving lessons and moving out all arrive. Providing for a partner who would struggle to return to full-time work is another. Where an estate might face an inheritance tax liability, the liability tends not to reduce simply because time passes, though the position depends on the estate and the rules that apply to it.</p>

<p>Level cover also gives you flexibility you may value later. If you move house, borrow more, or change mortgage type, a level policy does not need to keep pace with a schedule, because it was never tied to one.</p>

<h2>It is not always one or the other</h2>

<p>Some households hold both, which often reflects the shape of the need more honestly than a single policy. A decreasing policy sits against the repayment mortgage, and a separate level policy covers income replacement and family costs. Setting them up as separate policies rather than one combined arrangement can also mean that a claim on one does not automatically end the other, though whether that is the case depends entirely on how the policies are structured.</p>

<p>Term length is the other lever, and it is worth treating separately from the shape of cover. A policy running to the end of the mortgage and a policy running until the youngest child is financially independent are answering different questions, and those two dates are often years apart.</p>

<h2>What to check before you decide</h2>

<ul class="na-checklist">
  <li>Whether your mortgage is repayment, interest-only, or part and part</li>
  <li>The outstanding balance and the remaining term</li>
  <li>What assumed interest rate a decreasing policy uses for its reduction</li>
  <li>Whether the need extends beyond the mortgage, and by how much</li>
  <li>Whether you expect to move, extend or borrow more within the term</li>
  <li>The actual quoted premium difference between the two shapes on comparable terms</li>
</ul>

<h2>Common mistakes</h2>

<p>The most frequent one is choosing decreasing cover purely on price and never checking whether the reduction pattern matches the debt. The second is treating a mortgage-linked policy as though it were tied to the lender. Most are not: the policy is yours, the payout usually goes to your estate or your beneficiary rather than directly to the lender, and it can be used for whatever is most useful at the time. Whether a lender has any interest registered against a policy depends on how it was set up, so it is worth confirming.</p>

<p>A third is assuming a policy taken out with an old mortgage still fits the new one. Moving home does not automatically move the protection with it in a meaningful sense, even where the policy itself continues.</p>

<h2>What an adviser will want to understand</h2>

<ul class="na-checklist">
  <li>Your mortgage type, balance, remaining term and current interest rate</li>
  <li>Whether the cover is meant to clear a debt, replace an income, or both</li>
  <li>Any plans to move, remortgage or borrow further within the next few years</li>
  <li>Who is on the mortgage and who would need the money</li>
  <li>Existing policies, including anything arranged with a previous mortgage</li>
  <li>Your health and family medical history</li>
  <li>The monthly premium you could keep paying through a difficult period</li>
</ul>

<p class="na-disclaimer">This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.</p>');
    }

    public static function guide_family_income_benefit_explained(): string
    {
        return self::html('<nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a> <span aria-hidden="true">/</span> <a href="/guides/">Guides</a> <span aria-hidden="true">/</span> <span>Family income benefit</span></nav>

<p class="na-eyebrow">Life insurance</p>

<p class="na-lede">Family income benefit is life insurance that pays a regular income instead of a single lump sum. It is one of the less familiar options in the UK market, and for households whose real worry is the monthly bills, it can be one of the more intuitive.</p>

<h2>How it works</h2>

<p>You choose a monthly or annual amount and a term. If a valid claim is made during the term, the policy pays that amount regularly from the point of claim until the term ends. Then it stops.</p>

<p>The consequence of that structure is worth pausing on. The payments run to the policy\'s end date, not for a fixed number of years from the claim. A claim early in the term produces many more payments in total than a claim near the end. In that sense the total value of the policy decreases over the term, which is why it sits in the same family as decreasing term assurance rather than level term.</p>

<p>If no claim is made, the policy simply ends. Like other term assurance, it has no cash value and nothing is returned.</p>

<h2>Why the income shape helps</h2>

<p>The strongest argument for family income benefit is behavioural rather than technical. Most households do not think in six-figure lump sums. They think in monthly outgoings: the mortgage payment, the nursery fees, the food shop, the car. A policy that replaces a monthly figure maps directly onto that, and the sum insured is easier to sanity check, because you can compare it against a bank statement rather than a spreadsheet projection.</p>

<p>It also removes a difficult job from a grieving family. A large lump sum has to be looked after, and decisions about where to hold it and how much to draw are hard to make well in the months after a bereavement. A regular payment does not require those decisions to be made immediately.</p>

<div class="na-callout-grid">
  <div class="na-callout">
    <h3>Family income benefit</h3>
    <p>Pays a regular income from claim to the end of the term. Sized against monthly outgoings. Total payout falls as the term runs on.</p>
  </div>
  <div class="na-callout">
    <h3>Level term assurance</h3>
    <p>Pays a fixed lump sum whenever a valid claim occurs in the term. More flexible on the day, but the money has to be managed.</p>
  </div>
  <div class="na-callout">
    <h3>Using both</h3>
    <p>Some households pair a lump sum to clear the mortgage with an income benefit to cover living costs. The two jobs are different.</p>
  </div>
</div>

<h2>Cost, and why it tends to be lower</h2>

<p>For a given level of protection, family income benefit generally costs less each month than level term assurance covering an equivalent need. The reason is structural: the insurer\'s total exposure reduces year by year, in the same way it does with decreasing cover.</p>

<p>How much less depends on the term, the ages involved, health, whether the benefit is level or increasing, and the individual insurer\'s pricing. There is no reliable rule of thumb, so the sensible approach is to obtain quotations for both shapes for the same need and compare them directly.</p>

<h2>Level or increasing payments</h2>

<p>Many policies allow the benefit to be set as level, meaning the monthly amount stays the same throughout, or as increasing, where it rises each year either by a fixed percentage or in line with an index such as the Retail Prices Index.</p>

<p>Over a long term this choice matters more than it first appears, because a fixed monthly amount buys less as time passes. A benefit designed to cover a household\'s costs when a child is a toddler may look thin by the time that child is a teenager. Increasing cover addresses that, and the premium usually rises as well, sometimes in step with the benefit and sometimes on a different basis. The terms vary between insurers and are set out in the policy documents.</p>

<h2>Who it tends to suit, and who it does not</h2>

<p>Family income benefit is often discussed with households where the need is clearly time limited and clearly income shaped. Parents with young children are the classic example, because the dependency has a visible end point and the loss being insured against is a recurring one.</p>

<p>It fits less naturally where the need is a single, immediate cost. Clearing a mortgage, paying inheritance tax, or funding a one-off expense all call for a lump sum, and a monthly income does not do that job. It also fits less well where the need does not reduce over time, or where there is no defined end date to the dependency.</p>

<p>That is why the two are often discussed together rather than as rivals. A household might use one policy to deal with the debt and another to deal with the day to day, and sizing each one honestly usually produces a better answer than forcing a single policy to do both jobs.</p>

<h2>Points to check in the wording</h2>

<ul>
  <li>Can the benefit be commuted? Some insurers allow the remaining payments to be taken as a reduced lump sum instead. Some do not, and where the option exists the calculation basis differs.</li>
  <li>How is the first payment timed? Practice varies on whether payment starts from the date of death or the date the claim is accepted, and whether any arrears are covered.</li>
  <li>Is the benefit level or increasing, and how is the increase calculated? Index-linked and fixed-percentage increases behave differently over a long term.</li>
  <li>What happens on the final payment? Payments end with the term, with no separate closing sum unless the policy says otherwise.</li>
  <li>Is critical illness cover attached? Some versions pay an income on diagnosis of a specified condition as well as on death. Definitions of covered conditions are insurer specific and are the whole substance of that cover.</li>
</ul>

<h2>Tax and trusts</h2>

<p>Payments from a life policy are typically made free of income tax, though how any particular policy and payment are treated depends on how it is set up and on tax rules that can change. As with any life policy, the proceeds can form part of the estate for inheritance tax purposes unless the policy is written in trust.</p>

<p>Writing family income benefit in trust involves an extra consideration, because a stream of payments has to be received and passed on by trustees over a number of years rather than distributed once. That is entirely workable, but it means the choice of trustees and the type of trust deserve more thought than a one-off payment would need. It is worth taking advice on this, and on how any payments might interact with means-tested benefits or with other financial support, since that depends on individual circumstances.</p>

<h2>Common misunderstandings</h2>

<p>The most frequent is assuming the income runs for a set number of years from the date of claim. It normally runs to the policy\'s end date, which is why the term you choose at the outset does more work than any other decision in the application.</p>

<p>The second is choosing a monthly benefit equal to gross salary. What a household actually loses is the net amount that arrived in the account, so starting from take-home pay and known outgoings gives a truer figure.</p>

<p>The third is treating it as an alternative to protecting the mortgage. It can help meet mortgage payments month by month, but it does not clear the balance, and the lender\'s requirements do not change because an income is being paid.</p>

<h2>What an adviser will want to understand</h2>

<ul class="na-checklist">
  <li>Your household\'s regular outgoings, and which of them would continue</li>
  <li>Net monthly income by person, rather than gross salary</li>
  <li>The ages of any dependent children and when you expect the dependency to end</li>
  <li>Your mortgage balance, type and remaining term</li>
  <li>Whether you want the benefit to keep pace with inflation</li>
  <li>Existing cover, including employer benefits and any older policies</li>
  <li>Your health and family medical history</li>
  <li>Who should receive the payments, and whether a trust has been considered</li>
</ul>

<p class="na-disclaimer">This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.</p>');
    }

    public static function guide_how_much_income_protection_can_i_get(): string
    {
        return self::html('<nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a> <span aria-hidden="true">/</span> <a href="/guides/">Guides</a> <span aria-hidden="true">/</span> <span>How much income protection</span></nav>

<p class="na-eyebrow">Income protection</p>

<p class="na-lede">Income protection pays a monthly benefit if illness or injury stops you working, but insurers cap that benefit against your earnings rather than against what you would like to receive. This guide explains how the ceiling is worked out and what shifts it.</p>

<h2>Why there is a ceiling at all</h2>

<p>Insurers deliberately set the maximum benefit below your normal take-home pay. The reasoning is straightforward: a policy that paid more than you earn would give a financial reason to stay off work, and underwriters treat that as a risk they will not accept. So every insurer publishes a maximum benefit expressed as a proportion of your pre-tax earnings, sometimes with a higher proportion applied to the first slice of income and a lower one above it, and usually with an overall monetary cap on top.</p>

<p>The exact proportions, the income bands and the cap all vary between insurers, and they change over time. Two people with identical salaries can be offered different maximum benefits simply because they applied to different providers. That variation is one of the main reasons the sums are worth checking before you fix a figure in your head.</p>

<h2>How insurers define your earnings</h2>

<p>The word "income" does a lot of work here, and the definition in the policy wording is what counts, not the number on your payslip.</p>

<h3>If you are employed</h3>

<p>Basic salary is almost always included. Beyond that it depends on the insurer and the wording: regular overtime, shift allowances, commission and bonus may be included in full, averaged over a period of previous years, or excluded entirely. Benefits in kind such as a car allowance or private medical cover are sometimes counted and sometimes not. If a large share of your pay is variable, the difference between two insurers\' definitions can be substantial.</p>

<h3>If you are self-employed or a company director</h3>

<p>A sole trader\'s earnings are generally taken as net profit, the figure after allowable business expenses but before tax. For a partner it is normally your share of the partnership\'s net profit.</p>

<p>Company directors are the group most often caught out. Many take a small salary and the rest in dividends, so a definition based on salary alone would produce a very low maximum benefit. Some insurers will include dividends drawn from the business, and some will also include your share of retained profit, which can matter if you have deliberately left money in the company. Others will not. If you run a limited company, the earnings definition is worth reading before anything else in the quote.</p>

<p>Newly self-employed applicants face a further question: whether the insurer will work from a short trading history, from projections, or will want a full set of accounts. Practice differs and depends on the insurer.</p>

<h2>What gets deducted from the benefit</h2>

<p>The maximum you can insure is not always the maximum you will be paid. Many policies deduct, or take account of, other income you receive while unable to work. Depending on the wording that can include continuing income from your employer, benefit from an employer\'s group income protection scheme, certain state benefits, and income from an ill-health pension.</p>

<p>Some policies do this by reducing the benefit at claim. Others fix the benefit at outset and pay it in full regardless, which removes the uncertainty but means the amount you can insure at the start is lower. Whether other income is offset, and which sources count, depends on the individual policy wording and the insurer.</p>

<h2>The levers that change what you pay</h2>

<div class="na-callout-grid">
<div class="na-callout"><h3>Deferred period</h3><p>How long you wait after becoming unable to work before benefit starts. A longer wait generally reduces the premium, so the practical question is how long your sick pay, savings and any other cover would realistically hold out.</p></div>
<div class="na-callout"><h3>Benefit period</h3><p>How long benefit is paid for a single claim. Short-term versions stop after a set number of years; full-term versions can continue to your chosen end age while you remain unable to work under the policy definition.</p></div>
<div class="na-callout"><h3>Premium basis</h3><p>Guaranteed premiums are fixed at outset. Reviewable premiums start lower but can be increased by the insurer at review. Age-costed premiums rise each year by design.</p></div>
<div class="na-callout"><h3>Indexation</h3><p>Cover and premiums can be set to rise each year in line with an index, which keeps the benefit meaningful over a long term but increases the cost as it goes.</p></div>
</div>

<h2>Working out the figure you actually need</h2>

<p>The maximum available and the amount worth insuring are different questions. A useful way to approach the second is to work from your own numbers rather than from a proportion of salary.</p>

<ol>
<li>List your genuinely unavoidable monthly outgoings: mortgage or rent, council tax, utilities, food, insurance, childcare, travel to work if it would continue, and any debt repayments.</li>
<li>Add the costs that would carry on regardless, and remove anything that would genuinely stop if you were not working.</li>
<li>Subtract any income that would still arrive: a partner\'s earnings if you are budgeting jointly, rental income, and employer sick pay for the period it lasts.</li>
<li>The shortfall that remains is the gap the benefit is being asked to fill. Compare it with the insurer\'s maximum. If the gap is larger than the maximum, the difference has to be met some other way.</li>
</ol>

<p>Remember that income protection benefit is normally paid free of income tax on a personal policy, so a benefit set against your net position, rather than your gross salary, is usually the more realistic comparison. Tax treatment depends on individual circumstances and can change, and it differs for employer-funded schemes.</p>

<h2>Financial evidence: at application or at claim</h2>

<p>Insurers check earnings at two possible points. Some ask for financial evidence when you apply, which means the benefit is agreed up front. Others accept your stated income at application and verify earnings at the point of claim, which is where problems surface. If your income has fallen since you took the policy out, a claim-time check can reduce the benefit paid, even though you have been paying premiums based on the original figure.</p>

<p>Which approach applies is set out in the policy wording. Where earnings are checked at claim, the practical consequence is that the cover needs reviewing whenever your income changes materially, in either direction.</p>

<h2>What commonly goes wrong</h2>

<ul>
<li>Insuring against gross salary when the benefit is paid without income tax deducted, so the cover is higher, and more expensive, than the shortfall requires.</li>
<li>Company directors quoting salary only and ending up with a benefit far below what their total drawings would have supported.</li>
<li>Choosing a deferred period that matches sick pay entitlement on the day the policy starts, then changing jobs and never revisiting it.</li>
<li>Assuming an employer\'s group scheme is permanent. It usually ends when the employment does.</li>
<li>Setting the policy end age to state pension age without checking whether the mortgage or the dependants\' costs actually run that far.</li>
<li>Leaving cover flat for a decade while income and outgoings both rise.</li>
</ul>

<h2>What an adviser will want to understand</h2>

<ul class="na-checklist">
<li>How your income is made up: salary, overtime, commission, bonus, dividends, retained profit, and how stable each part is.</li>
<li>Your employment basis, and for company directors, how you draw money from the business.</li>
<li>Exactly what sick pay you get and for how long, including any half-pay period.</li>
<li>Whether an employer scheme already provides income protection, and what it would pay.</li>
<li>Your essential monthly outgoings and any income that would continue if you stopped work.</li>
<li>Savings you could realistically live on before benefit starts.</li>
<li>Your occupation and duties, since these affect both the definition offered and the cost.</li>
<li>Your medical history and any current conditions, which affect terms and exclusions.</li>
<li>Whether you want certainty on premiums or the lowest starting cost.</li>
<li>How long the cover needs to run, and what event ends the need.</li>
</ul>

<p class="na-disclaimer">This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.</p>');
    }

    public static function guide_how_much_life_insurance_do_i_need(): string
    {
        return self::html('<nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a> <span aria-hidden="true">/</span> <a href="/guides/">Guides</a> <span aria-hidden="true">/</span> <span>How much cover</span></nav>

<p class="na-eyebrow">Life insurance</p>

<p class="na-lede">There is no universal figure. The useful question is not what other people buy, but what this money would actually have to pay for, for whom, and for how long.</p>

<h2>Work out the job the money has to do</h2>

<p>A life insurance sum assured is not a score or a status symbol. It is a lump sum, or in some cases a monthly income, that lands at a moment when a household has lost a wage, a carer, or both. The amount is only sensible when you can describe what it is being asked to do.</p>

<p>Most people find it easier to build the figure from three separate piles rather than pluck one number out of the air:</p>

<ul>
  <li>Debts that would need clearing, so the survivors are not servicing borrowing on a reduced income.</li>
  <li>Income that would need replacing, for as long as someone depends on it.</li>
  <li>One-off costs that arrive because of the death, or because of the change in circumstances that follows.</li>
</ul>

<p>Add the piles up, then subtract what already exists. That last step matters and is skipped surprisingly often.</p>

<h2>The debts pile</h2>

<p>Start with the mortgage, because for most households it is the largest single commitment and the one with the harshest consequence if it is not met. Use the outstanding balance, not the original loan, and note the remaining term. If you have more than one mortgage, or a further advance taken later for building work, list them separately since they often run to different end dates.</p>

<p>Then add any other borrowing that would not simply disappear. Personal loans, car finance, credit card balances and any borrowing you have guaranteed for someone else all belong here. Some borrowing is covered by its own protection and some is not, and the terms vary by lender and by agreement, so it is worth reading what you already hold rather than assuming.</p>

<p>A point people miss: debts held in a single name do not automatically pass to a partner, but they are usually settled from the estate, which reduces what the estate can pass on. Joint debts are a different matter, because each borrower is generally liable for the whole amount. If you are unsure how a particular debt is held, the credit agreement or the lender can tell you.</p>

<h2>The income replacement pile</h2>

<p>This is where the arithmetic gets personal, and where the two common shortcuts both fail.</p>

<p>The first shortcut is to insure the mortgage only. That protects the roof and nothing else, which leaves a household with a home it can afford to keep and no money to run it. The second shortcut is to insure a big round number because it sounds reassuring. That can lead to premiums that get cancelled within a couple of years, which is the worst outcome of all, because the cover disappears at the point it was starting to be useful.</p>

<p>A more grounded method is to take the net monthly amount the household would actually miss, multiply it by twelve, and then multiply that by the number of years the dependency lasts. The dependency period is usually the honest constraint. Cover for a child aged three has a different runway to cover for a child aged fifteen. If a partner would return to work after a period of adjustment, the replacement period may be shorter than the full number of years to retirement.</p>

<p>Two refinements are worth thinking about. A lump sum invested may produce some return over time, which is not guaranteed and depends on how it is held. Inflation works the other way, eroding what a fixed sum buys across a long term. Some policies offer increasing cover, often linked to an index or rising at a set rate, and the premium usually rises alongside it. Whether that trade is worth making depends on the term length and your own circumstances.</p>

<div class="na-callout-grid">
  <div class="na-callout">
    <h3>The earner</h3>
    <p>Replacing a wage is the visible half of the problem. Use net income, not gross, because the household spends what arrives in the account.</p>
  </div>
  <div class="na-callout">
    <h3>The non-earner</h3>
    <p>Childcare, school runs, elderly care and household management have a real cost if they have to be bought in. A parent with no salary can still leave a substantial gap.</p>
  </div>
  <div class="na-callout">
    <h3>The single person</h3>
    <p>With no dependants and no joint debt, life cover may be doing very little. Cover against illness or incapacity may be the more relevant conversation.</p>
  </div>
</div>

<h2>Costs that arrive because of the death</h2>

<p>Funeral costs are the obvious one, and they vary enormously by choice and by region. Beyond that, there is often a period where normal earning stops entirely, because the surviving partner takes extended leave, reduces hours, or cannot work at all for a while. A buffer for that gap is more useful than most people expect.</p>

<p>Inheritance tax may also be relevant depending on the size and structure of the estate, and the rules interact with property, marriage and gifting in ways that are genuinely intricate. Life policy proceeds are typically paid free of income tax and capital gains tax, but the money can form part of the estate for inheritance tax purposes unless the policy is written in trust. Writing a policy in trust can also mean the money reaches the intended person without waiting for probate. Trusts have consequences of their own, and whether one is appropriate depends on your circumstances, so this is a point to take advice on rather than a box to tick.</p>

<h2>Subtract what you already have</h2>

<p>Before settling on a figure, take stock of existing provision. Employer death in service benefit is common and is often expressed as a multiple of salary, but it usually ends when the job does, and it may be paid at the discretion of trustees. Old policies bought alongside a previous mortgage may still be running. Pension schemes sometimes pay a lump sum, a dependant\'s pension, or both, and the rules differ between schemes.</p>

<p>State support exists but is limited and conditional. Bereavement Support Payment has eligibility rules tied to circumstances such as marriage, civil partnership and children, and it is not designed to replace a household income. Treat any state entitlement as a small offset rather than a plan.</p>

<ul class="na-checklist">
  <li>Current mortgage balance and remaining term, for each mortgage held</li>
  <li>Other debts, and whether each is in a sole or joint name</li>
  <li>Net monthly household income by person</li>
  <li>Ages of any children or other dependants</li>
  <li>Details of employer death in service and pension death benefits</li>
  <li>Any existing life policies, including old ones you think may have lapsed</li>
</ul>

<h2>Things that commonly go wrong</h2>

<p>Cover is bought and then never revisited. Households move, borrow more, separate, have another child, or change jobs, and the figure that made sense at the time quietly stops fitting.</p>

<p>Cover is arranged for one partner only, usually the higher earner, leaving a serious gap on the other side. Health information is answered casually at application, which can cause problems at claim, since insurers assess what was disclosed when the policy was set up. And cover is chosen at a premium the household cannot sustain through a tight year, which leads to cancellation.</p>

<h2>What an adviser will want to understand</h2>

<ul class="na-checklist">
  <li>Who depends on your income financially, and until roughly when</li>
  <li>What you owe, to whom, and over what remaining term</li>
  <li>What benefits you already hold through work, pensions or older policies</li>
  <li>Your health history and that of your family, in reasonable detail</li>
  <li>What you could comfortably afford to pay every month, in a bad year as well as a good one</li>
  <li>Whether you have a will, and whether anyone should be considered as a trust beneficiary</li>
  <li>Any changes you can already see coming, such as a house move or a child starting school</li>
</ul>

<p class="na-disclaimer">This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.</p>');
    }

    public static function guide_joint_vs_single_life_insurance(): string
    {
        return self::html('<nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a> <span aria-hidden="true">/</span> <a href="/guides/">Guides</a> <span aria-hidden="true">/</span> <span>Joint or single</span></nav>

<p class="na-eyebrow">Life insurance</p>

<p class="na-lede">One policy covering two people, or two policies covering one person each. The decision looks like a question about price, but it is really a question about what happens after the first claim.</p>

<h2>What each arrangement actually is</h2>

<p>A joint life policy covers two people under a single contract with a single sum assured. The most common version is joint life first death, which pays out when the first of the two people dies within the term, and then ends. There is no second payout, and no cover left for the survivor.</p>

<p>Two single life policies are two separate contracts. Each has its own sum assured, its own term, and its own premium. If one person dies, that policy pays and the other continues untouched.</p>

<p>There is also joint life second death, which pays only when the second person dies. It is not usually used to protect a household\'s living standard, because the money arrives when neither person needs an income. It appears more often in estate planning, where the aim is to provide a sum at the point an inheritance tax liability might arise. Whether that is relevant depends entirely on the estate and on rules that can change.</p>

<h2>The cost comparison, honestly</h2>

<p>A joint life first death policy is often cheaper than two comparable single policies, because the insurer is covering one payout rather than potentially two. It is not usually half the cost, though, since the chance of at least one of two people dying within a term is higher than the chance of one specific person doing so.</p>

<p>The gap varies by insurer, by age, by health and by the level of cover, and it can be narrower than people expect. It is worth asking for both quotations side by side rather than assuming which will win, because the assumption is sometimes wrong and the difference is often smaller than the difference in what you actually get.</p>

<div class="na-callout-grid">
  <div class="na-callout">
    <h3>Joint, first death</h3>
    <p>One contract, one sum assured, one payout. Simpler to administer and often cheaper, but it leaves the survivor with no cover.</p>
  </div>
  <div class="na-callout">
    <h3>Two single policies</h3>
    <p>Two payouts are possible. Each person can hold a different amount and term, and the policies can be dealt with separately if circumstances change.</p>
  </div>
  <div class="na-callout">
    <h3>Joint, second death</h3>
    <p>Pays after both have died. Used for estate planning purposes rather than income replacement. Suitability depends heavily on the estate.</p>
  </div>
</div>

<h2>The survivor problem</h2>

<p>This is the argument that carries the most weight, and it is easy to miss when comparing monthly costs.</p>

<p>After a joint life first death claim, the surviving partner has received a lump sum and has no life cover. If they still have dependent children, they now need cover more than before, not less, because they are the only remaining parent. To get it they must apply again, at an older age, and disclose any health conditions that have developed since the original application. Cover may be available on standard terms, it may come with an increased premium or an exclusion, or it may not be available at all. That depends on the individual and on each insurer\'s underwriting.</p>

<p>With two single policies, the survivor\'s cover continues on the terms and at the price agreed when they were younger and, quite possibly, healthier. Some joint policies include a separation or guaranteed insurability option that allows the survivor to take out a new policy without full medical underwriting, but this is not universal, the terms differ, and it is something to check in the wording rather than assume.</p>

<h2>What happens if you separate</h2>

<p>A joint policy is written on two lives, and it cannot simply be cut in half. If a relationship ends, the usual options are to cancel it, to keep paying it jointly, or, where the insurer offers it, to use a separation option to replace it with individual cover. Where a separation option exists it often has conditions attached, such as a time limit or a requirement that the original policy is cancelled.</p>

<p>Two single policies avoid the problem altogether, because each person already owns their own contract and can keep, change or cancel it independently. For unmarried couples in particular, where there is no automatic legal framework on separation, this independence can matter more than the monthly saving.</p>

<h2>Different needs, different amounts</h2>

<p>A joint policy imposes one sum assured on two people. That is fine where the need really is shared and identical, which is essentially the mortgage. It fits less well once you look past the mortgage, because two people rarely represent the same financial loss. One may earn considerably more. One may do most of the childcare, which has a real cost to replace. One may have a pension scheme that pays a dependant\'s benefit while the other does not.</p>

<p>Separate policies let you size each person\'s cover to what that person\'s absence would actually cost, and let you run different terms. Cover on a parent who does most of the caring might reasonably run until the youngest child finishes education, while cover on the higher earner might run to a different date entirely.</p>

<h2>Trusts, and where the money goes</h2>

<p>On a joint life first death policy, the proceeds normally go to the surviving policyholder, which is straightforward, though the money then sits in the survivor\'s estate. With single policies, writing the policy in trust is a common way to direct the proceeds to chosen beneficiaries, potentially without waiting for probate, and it can affect how the money is treated for inheritance tax purposes.</p>

<p>Trusts are not automatically the right answer. They involve giving up a degree of control, the choice of trust type matters, and the effect depends on your circumstances and on tax rules that can change. This is a point to take proper advice on, ideally at the time the policy is set up rather than years later.</p>

<h2>Where joint cover still makes good sense</h2>

<p>None of the above makes joint cover a poor arrangement. It is often a sensible fit where the entire need is a shared mortgage on a property both people own, where there are no children or other dependants, where budget is genuinely tight and the choice is between joint cover and no cover, or where one person cannot obtain their own cover on acceptable terms.</p>

<p>The mistake is not choosing joint cover. The mistake is choosing it without knowing what you have traded away.</p>

<h2>Things that commonly go wrong</h2>

<p>Couples set up joint cover for a mortgage, then have children, and never revisit it, so a policy sized for a debt is quietly expected to support a family. Or a joint policy is arranged with an old mortgage on a property that has since been sold. Or a claim is paid, and the surviving partner discovers only then that they have no cover of their own and now face fresh underwriting.</p>

<p>One further point worth confirming rather than assuming: how a policy treats the death of both people, for example in the same accident. Provisions differ between insurers and are set out in the wording.</p>

<h2>What an adviser will want to understand</h2>

<ul class="na-checklist">
  <li>Whether you are married, in a civil partnership, cohabiting, or neither</li>
  <li>Who owns the property, who is on the mortgage, and in what shares</li>
  <li>Each person\'s income, and what each contributes that would need replacing</li>
  <li>Whether there are dependent children, and their ages</li>
  <li>Both people\'s health and family medical history, since one person\'s history can affect a joint quotation</li>
  <li>Any existing cover on either life, including employer benefits</li>
  <li>Who you would want the money to reach, and whether a trust has been considered</li>
  <li>Your total monthly budget for protection across both lives</li>
</ul>

<p class="na-disclaimer">This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.</p>');
    }

    public static function guide_life_insurance_and_inheritance_tax(): string
    {
        return self::html('<nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a> <span aria-hidden="true">/</span> <a href="/guides/">Guides</a> <span aria-hidden="true">/</span> <span>Life cover and IHT</span></nav>

<p class="na-eyebrow">Estate planning</p>

<p class="na-lede">Life insurance can make an inheritance tax problem worse or help solve it, and the difference usually comes down to how the policy is set up. This guide explains the interaction, in general terms, and where professional advice is needed.</p>

<h2>Two different conversations</h2>

<p>People ask about life insurance and inheritance tax for two quite different reasons, and it helps to separate them.</p>

<p>The first is defensive: making sure an existing life policy does not itself create or increase a tax bill. The second is deliberate: using a policy to provide the money to pay an inheritance tax liability that is expected to arise on death. The mechanics overlap, but the questions are not the same.</p>

<h2>How a payout can end up in your estate</h2>

<p>Inheritance tax is charged on the value of your estate on death, after available reliefs, exemptions and allowances. If a life policy pays into your estate, the payout increases that value.</p>

<p>That is what happens by default when a policy is not written in trust and has no other valid nomination in place. The proceeds are paid to your personal representatives, they form part of the estate, and they are dealt with under your will or under the intestacy rules if there is no will. There is a second consequence too: the money is not accessible until probate is granted, which can take time at exactly the point a family needs funds.</p>

<p>Writing the policy in trust generally changes both outcomes. The benefit is held for the beneficiaries rather than for your estate, trustees can normally claim without waiting for probate, and the proceeds are usually outside the estate for inheritance tax. The detail depends on the type of trust used and on your circumstances, and our separate guide on putting life insurance in trust covers the process.</p>

<h2>The main allowances and exemptions, in outline</h2>

<p>The framework matters more than any single number, particularly since the numbers change. In broad terms:</p>

<ul>
<li>A nil rate band applies to each individual estate, below which no inheritance tax is charged.</li>
<li>An additional residence nil rate band may be available where a qualifying residential property passes to direct descendants, subject to conditions and to a taper where the estate is large.</li>
<li>Transfers between spouses and civil partners who are UK domiciled are generally exempt, and unused allowances can often be transferred to the survivor.</li>
<li>Certain reliefs may apply to qualifying business or agricultural assets, subject to conditions that have been the subject of recent change.</li>
</ul>

<p>Tax treatment depends on individual circumstances and current rules, both of which can change. The current thresholds, conditions and rates should always be checked at the time, and confirmed with a tax adviser or solicitor rather than taken from a general guide.</p>

<h2>Why the liability often falls on the second death</h2>

<p>For married couples and civil partners, the spouse exemption usually means little or no inheritance tax arises when the first partner dies. The tax charge, if there is one, tends to arise when the survivor dies and the estate passes to children or others.</p>

<p>That is why policies arranged specifically to meet an inheritance tax liability are commonly written as joint life second death, paying out when the second of two people dies. A joint life first death policy, which is the usual shape for mortgage and family protection, pays at the wrong moment for this particular purpose.</p>

<p>Timing matters in another way. The liability arises on death whenever that happens, so a policy intended to cover it generally needs to last for life rather than for a fixed term. Whole of life cover is the product type usually used, and the trade-offs are covered below.</p>

<div class="na-callout-grid">
<div class="na-callout"><h3>Term assurance</h3><p>Runs for a fixed period and pays nothing if you survive it. Lower cost for a given sum assured, but it can expire before the liability arises.</p></div>
<div class="na-callout"><h3>Whole of life</h3><p>Designed to pay whenever death occurs, provided premiums continue. Guaranteed premium versions cost more at outset; reviewable versions can be increased at review dates, sometimes significantly at older ages.</p></div>
<div class="na-callout"><h3>Joint life second death</h3><p>Pays on the second death of two lives, which matches when a couple\'s inheritance tax liability typically falls. Usually cheaper than two single policies for the same total sum.</p></div>
<div class="na-callout"><h3>Gift inter vivos cover</h3><p>Decreasing cover designed to sit alongside a lifetime gift, reducing in line with the taper that can apply where death occurs within the relevant period after the gift.</p></div>
</div>

<h2>Lifetime gifts and the seven-year rule</h2>

<p>Many people reduce a potential inheritance tax liability by giving assets away during their lifetime. Most outright gifts to individuals are potentially exempt transfers: if you survive for seven years from the date of the gift, they generally fall outside your estate. If you die within that period, the gift can be brought back into the calculation, and taper relief may reduce the tax due where death occurs later in that period.</p>

<p>Gift inter vivos cover exists to meet that risk. It is a decreasing term policy structured to reflect the reducing exposure over the relevant period. The recipient of the gift is often the person who would face the tax charge, so the policy is normally arranged with that in mind, and it is usually written in trust.</p>

<h2>Paying the premiums without creating a new problem</h2>

<p>If you pay premiums on a policy held in trust for someone else, those payments are themselves transfers of value. Several exemptions can apply, including the annual exemption, the exemption for normal expenditure out of income, and exemptions for certain gifts. Relying on the normal expenditure exemption in particular requires that payments are regular, made from income rather than capital, and do not reduce your standard of living, and it depends on keeping proper records.</p>

<p>The type of trust also matters. Discretionary trusts can be subject to their own inheritance tax charges at ten-year anniversaries and when capital leaves the trust, and reporting obligations can apply. For a pure protection policy with no surrender value these charges are often not an issue in practice, but the position is not automatic and should be confirmed.</p>

<h2>What commonly goes wrong</h2>

<ul>
<li>A large life policy left outside a trust, so the payout increases the estate and increases the tax charged on it.</li>
<li>A term policy bought to cover an inheritance tax liability, which then expires while the person is still alive.</li>
<li>A joint life first death policy used where a second death policy was the shape that matched the liability.</li>
<li>Reviewable whole of life premiums accepted at outset without allowing for the possibility of increases at later reviews.</li>
<li>An existing policy put into trust years after it started, which can itself be a transfer of value.</li>
<li>Business owners assuming a relief will still apply on death, when the qualifying conditions and the rules themselves may have changed.</li>
<li>The insurance being arranged while the will and the trust deeds say something different, so the pieces do not work together.</li>
</ul>

<p>Insurance is only one part of estate planning. Wills, trusts, lifetime gifting, business structures and pension nominations all interact, and legal or tax advice may be needed alongside insurance advice. Nest Assured advises on protection insurance and does not provide legal or tax advice.</p>

<h2>What an adviser will want to understand</h2>

<ul class="na-checklist">
<li>A broad picture of your assets and liabilities, including property, savings, investments and business interests.</li>
<li>Your marital or civil partnership status and whether allowances may have been used or transferred.</li>
<li>Whether you have a valid, up to date will, and when it was last reviewed.</li>
<li>Any significant lifetime gifts you have already made and when.</li>
<li>Existing life policies, their sums assured, their terms, and whether each is written in trust.</li>
<li>Whether a solicitor or tax adviser is already involved, and what their view of the liability is.</li>
<li>Who you would want to receive the proceeds, and whether any beneficiary is a minor or vulnerable.</li>
<li>Whether premiums would be paid from income or capital, and whether they are sustainable long term.</li>
<li>Your health and age, which determine whether whole of life cover is available and on what terms.</li>
<li>How you feel about premiums that can be reviewed, compared with paying more for a guaranteed rate.</li>
</ul>

<p class="na-disclaimer">This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.</p>');
    }

    public static function guide_life_insurance_and_mortgages(): string
    {
        return self::html('<nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a> <span aria-hidden="true">/</span> <a href="/guides/">Guides</a> <span aria-hidden="true">/</span> <span>Life cover and your mortgage</span></nav>

<p class="na-eyebrow">Mortgage protection</p>

<p class="na-lede">Buying a home is the point at which most people first think about life insurance, and it is also the point at which the most avoidable mistakes get made. Here is how the two actually connect.</p>

<h2>Is life insurance compulsory with a mortgage?</h2>

<p>For a standard UK residential mortgage, buildings insurance is normally a condition of the loan, because the lender is protecting the asset it has lent against. Life insurance is a different matter and is generally not a condition of the mortgage offer, although lenders and advisers will usually raise it and some may make a strong case for it. Requirements can differ by lender and by product, so if you have been told cover is mandatory, it is fair to ask where that requirement is written down.</p>

<p>You are also not obliged to buy cover from whoever arranged the mortgage. Life insurance is a separate contract and can be arranged separately, before or after the mortgage completes.</p>

<h2>What actually happens if a borrower dies</h2>

<p>The mortgage does not disappear. It remains a debt secured on the property, and the lender is entitled to be repaid according to the terms of the loan.</p>

<p>How the property itself passes depends on how it is owned. Where a property is held as joint tenants, the deceased\'s share typically passes automatically to the surviving owner by survivorship, outside the will. Where it is held as tenants in common, the share passes according to the will, or under the intestacy rules if there is no will, and that share may go to someone other than the co-owner. Many people are unclear which form of ownership they have, and it is worth checking, because it changes who ends up holding the property and the debt.</p>

<p>On a joint mortgage, borrowers are usually jointly and severally liable, which means each borrower can be held responsible for the whole loan rather than a half share. In practice a lender will often work with a bereaved survivor, and may allow a period of grace or a change to the payment arrangement, but that is a matter for the lender, not an entitlement.</p>

<p>Unmarried couples and cohabiting owners face the sharpest version of this, because there is no automatic legal inheritance between them. Without a will and without cover, a surviving partner can find themselves negotiating over a property they live in and a debt they may be liable for.</p>

<h2>Where the money goes</h2>

<p>A life policy taken out alongside a mortgage is usually your policy, not the lender\'s. The payout normally goes to your estate, to a named beneficiary, or to trustees, and the family then chooses what to do with it. Clearing the mortgage is the obvious use, but it is not the only one. A household might keep the mortgage running on a low rate and use the money for living costs instead. Occasionally a lender has an interest formally registered against a policy, in which case the position is different, so it is worth confirming how any existing arrangement is set up.</p>

<h2>Timing, and the gap that catches people out</h2>

<p>The riskiest moment in a house purchase is often the one nobody plans for. In England, Wales and Northern Ireland, you become legally committed on exchange of contracts, which can be weeks before completion. In Scotland, the equivalent commitment comes when missives are concluded. If something happens between that commitment and the cover starting, the liability exists without protection behind it.</p>

<p>Underwriting can also take longer than expected. If an insurer asks for a medical report from your GP, the wait depends on the practice, and it can run on. Starting the application early gives that process room, and an application that is decided quickly can simply have its start date arranged to suit.</p>

<div class="na-callout-grid">
  <div class="na-callout">
    <h3>Life cover</h3>
    <p>Pays out on death within the term. Deals with the debt and the loss of income when someone dies.</p>
  </div>
  <div class="na-callout">
    <h3>Critical illness cover</h3>
    <p>Pays a sum on diagnosis of a specified condition meeting the policy definition. Conditions and definitions differ significantly between insurers.</p>
  </div>
  <div class="na-callout">
    <h3>Income protection</h3>
    <p>Pays a monthly benefit if illness or injury stops you working, after a chosen waiting period. Aimed at keeping payments going, not clearing the loan.</p>
  </div>
</div>

<p>These three answer different questions, and the mortgage is a reminder rather than the whole conversation. Being unable to work for a long period puts a mortgage at risk just as surely as a death does, and it is the more likely event during a working life. Mortgage payment protection insurance is a further, narrower product that covers monthly payments for a limited period, usually with conditions attached to how the inability to work arose.</p>

<h2>Matching cover to the loan</h2>

<p>Three things need to line up: the amount, the term, and the shape.</p>

<p>The amount should reflect the outstanding balance rather than the original advance, and should account for any further borrowing. The term is normally set to run at least as long as the mortgage, and often longer if there are other needs such as dependent children. The shape, level or decreasing, depends on whether the mortgage is repayment or interest-only, and on whether the cover is also meant to replace income. An interest-only mortgage does not reduce its capital, so cover that reduces will drift away from it.</p>

<p>One structural decision is worth raising early. Cover arranged as a single joint policy pays once and ends, leaving the survivor without cover. Two single policies keep the survivor protected. The right answer depends on the household, but it is a decision that should be made deliberately rather than by default.</p>

<h2>Remortgaging, moving and porting</h2>

<p>Protection tends to be set up once and then forgotten, while mortgages change repeatedly. Every one of the following is a reason to look at cover again:</p>

<ul>
  <li>Moving to a larger property and a larger loan</li>
  <li>Remortgaging for a further advance, home improvements or debt consolidation</li>
  <li>Extending the mortgage term to reduce monthly payments</li>
  <li>Switching between repayment and interest-only, or to part and part</li>
  <li>Adding or removing a borrower</li>
  <li>Separation, where the mortgage is restructured onto one name</li>
</ul>

<p>An important practical point: never cancel an existing policy until the replacement is confirmed as on risk. Health changes between one application and the next, and a policy underwritten when you were younger and well may not be replaceable on the same terms. Where you already hold cover, keeping it and adding to it is sometimes possible, and worth exploring before assuming it needs to be replaced.</p>

<h2>Disclosure, and why it matters at claim</h2>

<p>Insurers assess the information given at application. Answering health, lifestyle and occupation questions carefully, and mentioning things that feel minor or embarrassing, protects the claim rather than jeopardising it. Under UK consumer insurance rules you are expected to take reasonable care not to make a misrepresentation, and a careless or deliberate one can affect what an insurer pays. If you are unsure whether something counts, disclose it and let the insurer decide.</p>

<h2>What an adviser will want to understand</h2>

<ul class="na-checklist">
  <li>Your mortgage balance, type, remaining term and expected completion date</li>
  <li>Whether the property is owned as joint tenants or tenants in common</li>
  <li>Who is named on the mortgage and who lives in the property</li>
  <li>Whether you are married, in a civil partnership, or cohabiting, and whether you have a will</li>
  <li>Each borrower\'s income, occupation and sick pay arrangements</li>
  <li>Employer benefits such as death in service and any pension death benefits</li>
  <li>Any existing protection policies, including cover taken with a previous mortgage</li>
  <li>Health and family medical history for everyone to be covered</li>
  <li>The monthly amount you could sustain alongside the new mortgage payment</li>
</ul>

<p class="na-disclaimer">This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.</p>');
    }

    public static function guide_life_insurance_for_over_50s(): string
    {
        return self::html('<nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a> <span aria-hidden="true">/</span> <a href="/guides/">Guides</a> <span aria-hidden="true">/</span> <span>Cover in your fifties and sixties</span></nav>

<p class="na-eyebrow">Life stages</p>

<p class="na-lede">Protection in your fifties and sixties is a different exercise from protection in your thirties. The needs have usually changed shape, the underwriting is more involved, and the policies you already hold matter as much as anything new.</p>

<h2>Why the question comes up now</h2>

<p>People rarely arrive at this in the abstract. It is usually triggered by something: a mortgage term running out, an existing policy approaching its end date, retirement coming into view, a parent dying, a health scare, or a conversation about what will happen to the house.</p>

<p>The underlying financial picture has often moved a long way from where it was two decades earlier. The mortgage may be smaller or gone. Children may be financially independent, or may still be at university, or may have moved back home. There may be a second family with younger dependants. There may be an estate large enough to face an inheritance tax charge, or a business that needs orderly succession. The point is that the need has changed rather than disappeared, and it is worth being precise about what it now is before looking at products.</p>

<h2>Your existing cover is the starting point</h2>

<p>Before considering anything new, it is worth establishing exactly what you already have. In this age group there are usually more pieces than people remember.</p>

<ul>
<li>Old term policies. Check the end date, the sum assured, whether it is level or decreasing, and whether it was taken out alongside a mortgage that no longer exists.</li>
<li>Death in service through work. Typically a multiple of salary, and typically it ends when the employment ends. Retiring or being made redundant removes it. It is also usually not portable.</li>
<li>Pension death benefits. Defined benefit schemes often provide a spouse\'s or dependant\'s pension. Defined contribution pots usually pass to nominated beneficiaries. Both depend on scheme rules and on your nomination being up to date.</li>
<li>Older whole of life or endowment-linked plans. These may have reviewable premiums or a review position you have never seen.</li>
<li>Mortgage-linked cover taken out decades ago. Often cheap by current standards, sometimes forgotten, occasionally still assigned to a lender that has been repaid.</li>
</ul>

<p>Old cover that is still in force and still priced on your health decades ago is not something to give up lightly. Cancelling it before you know what replaces it is one of the more damaging things people do at this stage.</p>

<h2>Underwriting in your fifties and sixties</h2>

<p>Two things change as you get older. Insurers price on age at application, so the same cover applied for later generally costs more than it would have earlier, all other things being equal. And medical history accumulates, so applications are more likely to involve questions, a GP report, or a nurse screening.</p>

<p>That does not mean cover is unavailable. It means the range of outcomes is wider than a simple yes or no. Depending on the insurer and the disclosure, an application can result in:</p>

<div class="na-callout-grid">
<div class="na-callout">
<h3>Standard terms</h3>
<p>The application is accepted at the ordinary price for your age. Many conditions that feel significant to the applicant are treated as routine by underwriters.</p>
</div>
<div class="na-callout">
<h3>A rating</h3>
<p>Cover is offered with an increased premium reflecting the assessed risk. The size of any loading depends entirely on the insurer\'s underwriting of your individual case.</p>
</div>
<div class="na-callout">
<h3>An exclusion</h3>
<p>More common on critical illness and income protection than on life cover. A specified condition or body part is written out of the policy.</p>
</div>
<div class="na-callout">
<h3>Postponement or decline</h3>
<p>The insurer may want to wait until after treatment, results, or a period of stability. Different insurers take different views of the same history, which is why the choice of insurer matters.</p>
</div>
</div>

<p>Whatever the outcome, disclose fully and accurately. Answering the questions asked, completely and honestly, is what protects the claim. An insurer that discovers something material at claim stage can revisit the policy, and the consequences fall on the people you were trying to protect.</p>

<h2>Term length is constrained by age</h2>

<p>Term assurance is normally subject to a maximum age at the end of the term, set by each insurer. In practice this means that at sixty you cannot always buy a thirty-year term, and the terms available may not stretch as far as you would like. It also means that the gap between what a term policy will cover and what a permanent need requires becomes more visible.</p>

<p>Where the need genuinely runs to the end of life, such as funeral costs or an expected inheritance tax charge, term cover with a fixed expiry does not match it. That is the territory whole of life cover is designed for, with the trade-offs that come with it. Where the need is still finite, for example clearing an interest-only mortgage balance at a known date or supporting a dependant through a defined period, term cover remains the direct answer.</p>

<h2>Underwritten cover and guaranteed acceptance plans are not the same thing</h2>

<p>Television advertising in this age bracket is dominated by over-50s guaranteed acceptance plans. They are sold without medical questions to people within a set age band, which is genuinely useful for someone whose health makes underwritten cover difficult.</p>

<p>They also behave differently from underwritten life insurance. There is normally a waiting period at the start, during which death from natural causes returns premiums or a limited amount rather than the full sum assured. Premiums typically continue for life or until a stated age, which means it is possible to pay in more than the plan will pay out if you live long enough. Stopping payments usually ends the plan with no value. None of this makes them wrong; it makes them a different product with a different purpose, and the individual plan terms decide the detail.</p>

<p>Anyone in reasonable health should at least understand what an underwritten application would produce before assuming a guaranteed acceptance plan is the only route.</p>

<h2>It is not only life cover</h2>

<p>Death is not the only risk in this decade, and for people still working it may not be the most likely one. Income protection pays a benefit if illness or injury stops you working, and its value depends on how many working years remain and what sick pay your employer provides. Critical illness cover pays a lump sum on diagnosis of a condition that meets the policy definition, and definitions vary substantially between insurers.</p>

<p>Both become more expensive and more heavily underwritten with age, and both have maximum ages for entry and cessation. Whether either is appropriate depends on your circumstances, your existing arrangements and your priorities.</p>

<h2>Getting the money to the right people</h2>

<p>A policy is only half the job. Writing life cover under an appropriate trust is intended to direct the proceeds to chosen beneficiaries and keep them outside the estate, which can also avoid waiting for probate. Whether a trust is suitable, and which type, depends on your situation and on what the policy is for.</p>

<p>Alongside that, this is the age at which pension nominations, wills and beneficiary details commonly go stale. Divorce, remarriage, a death in the family, or estrangement can all leave paperwork pointing at the wrong person. Reviewing the policy is not much use if the instructions attached to it are twenty years out of date.</p>

<h2>What an adviser will want to understand</h2>

<ul class="na-checklist">
<li>Every policy you currently hold, including old ones, with the end dates, sums assured and premium types.</li>
<li>What benefits your employer or pension scheme provides on death or long-term sickness, and when they stop.</li>
<li>Any mortgage or other debt still outstanding, particularly interest-only balances and their repayment dates.</li>
<li>Who depends on you financially now, and for how long that is likely to continue.</li>
<li>Your full medical history, including investigations that came back clear, and any family history the application asks about.</li>
<li>When you expect to stop working, and what your income will look like afterwards.</li>
<li>Whether you expect your estate to face an inheritance tax charge, and what your will currently says.</li>
<li>What premium is genuinely sustainable once you are no longer earning, and whether affordability or certainty matters more to you.</li>
</ul>

<p class="na-disclaimer">This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.</p>');
    }

    public static function guide_life_insurance_medical_underwriting(): string
    {
        return self::html('<nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a> <span aria-hidden="true">/</span> <a href="/guides/">Guides</a> <span aria-hidden="true">/</span> <span>Medical underwriting</span></nav>

<p class="na-eyebrow">Health and underwriting</p>

<p class="na-lede">Medical underwriting is how an insurer decides whether to offer you cover and on what terms. This guide walks through what actually happens, from the application questions to the final decision, and what tends to slow it down.</p>

<h2>Where underwriting begins</h2>

<p>Underwriting starts the moment you answer the health questions on an application. Those questions are not a formality. They are the primary evidence the insurer works from, and everything that follows is either a check on them or a request for more detail about something you have disclosed.</p>

<p>Application questions usually cover your height and weight, smoking or nicotine use, alcohol intake, current and past medical conditions, medication, hospital admissions and referrals, tests and investigations, family medical history for close relatives, your occupation, and sometimes hazardous pursuits and foreign travel. Life, critical illness and income protection applications ask overlapping but not identical questions, because each product is assessing a different kind of risk.</p>

<p>Answer what is asked, in the terms your medical records would use, and take the time to check dates rather than estimating. If you are unsure whether something is relevant, include it. The underwriter can disregard information that does not matter to them, but cannot assess what they were never told.</p>

<h2>The tele-interview or screening call</h2>

<p>Many insurers now follow the application with a telephone interview, either conducted by the insurer or by a specialist medical screening company. It is usually arranged at a time that suits you and is carried out by someone trained to ask medical questions.</p>

<p>The purpose is to clarify and expand on what you have already disclosed, and to ask follow-up questions the application form is too blunt to cover. It often replaces a full GP report, because a clear account given directly by you can be quicker and more informative than waiting for a surgery to respond.</p>

<p>It is worth preparing. Have your repeat prescription list, the names of any consultants you have seen, approximate dates of investigations, and any test results you hold. A vague answer usually creates a follow-up request, and follow-up requests are what make applications take a long time.</p>

<h2>GP reports</h2>

<p>An insurer may ask your GP for a report. This cannot happen without your written consent, and you will be asked to give it as part of the process. Reports come in two broad forms: a targeted report asking about a specific condition or period, or a fuller report covering your medical history.</p>

<p>Under the Access to Medical Reports Act 1988 you have the right to ask to see a report prepared by a doctor responsible for your care before it is sent to the insurer, and to ask the doctor to amend anything you believe is factually incorrect. You will normally be told about this right when consent is requested.</p>

<p>GP reports are the most common cause of delay. Practices handle them alongside clinical work, and turnaround varies considerably between surgeries. Nothing in the process is unusual or a sign of a problem, and a request for a report does not mean the insurer intends to decline the application.</p>

<h2>Medical screening and examinations</h2>

<p>Depending on the amount of cover, your age and what has been disclosed, an insurer may arrange a medical screening. This is usually carried out by a nurse, often at your home or workplace, and typically involves height and weight, blood pressure, and a blood or urine sample. Larger cases or particular disclosures can lead to a fuller examination or specific tests such as an electrocardiogram.</p>

<p>Screenings are arranged and paid for by the insurer. Occasionally a screening picks up something you were not aware of, such as a blood pressure or blood test reading outside normal ranges. If that happens, the insurer will normally tell you or ask you to speak to your GP. Insurers cannot pass results to your GP without your agreement.</p>

<h2>What the underwriter does with it all</h2>

<p>An underwriter assesses the whole picture rather than any single item. They are considering how a condition has been treated, how stable it is, how long ago it happened, what other risk factors sit alongside it, how much cover is being applied for, and over what term. Two people with the same diagnosis can receive different decisions because the surrounding detail differs.</p>

<div class="na-callout-grid">
<div class="na-callout"><h3>Ordinary terms</h3><p>Cover is offered at the insurer\'s standard rates with no alteration to the premium or the policy wording.</p></div>
<div class="na-callout"><h3>A rating</h3><p>Cover is offered at an increased premium reflecting the assessed risk. The policy pays out in exactly the same circumstances as one on ordinary terms.</p></div>
<div class="na-callout"><h3>An exclusion</h3><p>A specific condition or cause is written out of the policy. This is more common on critical illness and income protection than on life cover.</p></div>
<div class="na-callout"><h3>Postponement or decline</h3><p>A decision is deferred until a condition stabilises, treatment completes or results arrive, or the insurer concludes it cannot offer terms at present.</p></div>
</div>

<p>Whatever the outcome, ask for the reasoning. Underwriters will usually explain the basis of a decision to an adviser, and that explanation is what tells you whether the issue is the condition itself, the timing, the amount of cover, or a piece of missing evidence.</p>

<h2>Are you covered while the application is being assessed</h2>

<p>Not automatically. A policy generally provides no cover until it has been accepted, put on risk and the first premium has been collected. Some insurers offer temporary or immediate cover during underwriting, but where it exists it is usually limited in amount, limited in duration, restricted to accidental death, and subject to its own conditions.</p>

<p>Two things follow from this. First, check what, if anything, is in place during the application rather than assuming. Second, do not cancel any existing cover until the new policy is confirmed as in force and you have accepted the terms.</p>

<h2>Non-disclosure and what it means at claim</h2>

<p>Under the Consumer Insurance (Disclosure and Representations) Act 2012 you must take reasonable care not to make a misrepresentation when applying. If a claim is made and the insurer finds information that was not given accurately, its response depends on whether the misrepresentation was careless or deliberate, and on what the insurer would have done had it known.</p>

<p>Outcomes range from the claim being paid as normal, to being paid at a reduced level, to the policy being treated as if different terms had applied, to a claim being refused and the policy voided in the most serious cases. If you believe a claim decision is unfair, you can raise a complaint with the insurer and, if unresolved, refer it to the Financial Ombudsman Service.</p>

<p>The practical takeaway is simple. The single most useful thing you can do for a future claim is to answer every question fully and accurately now, and to keep a copy of what you submitted.</p>

<h2>How long it takes, and what slows it down</h2>

<p>A straightforward application with no medical evidence required can complete quickly. Anything requiring a GP report, a specialist report or a screening takes longer, and the timescale is largely outside the insurer\'s control.</p>

<ul class="na-checklist">
<li>Respond to consent forms and interview requests promptly, as the process pauses until you do.</li>
<li>Have your prescription list, consultant names and test dates ready before the tele-interview.</li>
<li>Tell the insurer if your health changes between applying and cover starting.</li>
<li>Check whether premiums are guaranteed or reviewable before accepting terms.</li>
<li>Keep a copy of the completed application and any medical evidence you supplied.</li>
</ul>

<h2>What an adviser will want to understand</h2>

<ul class="na-checklist">
<li>Your full medical history, including anything you consider minor or historic.</li>
<li>Current medication and dosage, plus anything recently started or stopped.</li>
<li>Any ongoing investigations, referrals or results you are waiting for.</li>
<li>Your height, weight, smoking or nicotine use and alcohol intake.</li>
<li>Family history of serious illness in close relatives.</li>
<li>Your occupation and duties, and any hazardous activities or travel.</li>
<li>Previous insurance applications that were rated, excluded, postponed or declined.</li>
<li>Which product you are applying for and what the cover needs to achieve.</li>
<li>How quickly the cover is needed, for example where a property purchase is involved.</li>
</ul>

<p class="na-disclaimer">This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.</p>');
    }

    public static function guide_life_insurance_pre_existing_conditions(): string
    {
        return self::html('<nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a> <span aria-hidden="true">/</span> <a href="/guides/">Guides</a> <span aria-hidden="true">/</span> <span>Pre-existing conditions</span></nav>

<p class="na-eyebrow">Health and underwriting</p>

<p class="na-lede">Having a health condition does not automatically rule out life cover in the UK, but it does change how an insurer looks at your application. This guide explains what insurers ask, the terms they can offer, and where applications tend to go wrong.</p>

<h2>What counts as a pre-existing condition</h2>

<p>Insurers use the phrase loosely. In practice it covers anything about your health that existed before you applied, whether or not you think of it as an illness. That includes conditions you are currently treated for, conditions you have recovered from, symptoms you have seen a GP about even without a diagnosis, tests you are waiting on, and medication you take regularly.</p>

<p>It is broader than most people expect. A back problem that flares up occasionally, a period of anxiety or low mood treated years ago, raised blood pressure controlled by tablets, an investigation that came back clear, a family history of a hereditary condition: all of these are things an application form is likely to ask about. So are height and weight, alcohol intake, and whether you have been referred to a specialist.</p>

<p>You are not being asked to diagnose yourself or to judge what matters. You are being asked to answer the specific questions in front of you honestly and completely, and to let the insurer decide what is relevant.</p>

<h2>Why insurers ask at all</h2>

<p>Most UK life cover is priced once, at the start, and the premium on a guaranteed rate policy then stays the same for the whole term regardless of what happens to your health afterwards. That is only possible if the insurer assesses the risk properly at the outset. Underwriting is the process of doing that.</p>

<p>This has a useful consequence. Once a policy is in force on guaranteed terms, a later diagnosis does not change your premium and does not give the insurer a route to cancel the cover, provided the information you gave at application was accurate. The scrutiny happens at the front end precisely so the cover is stable afterwards.</p>

<h2>The terms an insurer can offer</h2>

<p>An application does not simply pass or fail. There is a range of possible outcomes, and which one applies depends entirely on the insurer, the condition, and your wider circumstances.</p>

<div class="na-callout-grid">
<div class="na-callout"><h3>Standard terms</h3><p>The application is accepted at the insurer\'s ordinary rates with no changes. This is more common than people expect, including for well-controlled or historic conditions.</p></div>
<div class="na-callout"><h3>A rating</h3><p>Cover is offered, but at a higher premium than standard rates to reflect the assessed risk. The policy itself works in the same way and pays out in the same circumstances.</p></div>
<div class="na-callout"><h3>An exclusion</h3><p>Cover is offered but a specified cause or condition is written out of the policy. Exclusions are more often seen on critical illness and income protection than on life cover.</p></div>
<div class="na-callout"><h3>Postponement or decline</h3><p>The insurer may defer a decision until a diagnosis settles, treatment finishes or a recovery period passes, or may decide it cannot offer terms at that time.</p></div>
</div>

<p>Insurers reach these decisions using their own underwriting manuals, and those manuals differ. Two insurers looking at identical medical information can and do arrive at different answers, which is why one decision is not the whole picture.</p>

<h2>Answering the questions properly</h2>

<p>Under the Consumer Insurance (Disclosure and Representations) Act 2012, an applicant must take reasonable care not to make a misrepresentation when applying for cover. That is a duty to answer the insurer\'s questions carefully and accurately, rather than a duty to guess what the insurer might want to know.</p>

<p>Where it goes wrong is usually not deliberate. People forget a consultation from several years ago, describe a condition in vaguer terms than their medical records use, leave out a medication they no longer take, or assume something is too minor to mention. If a misrepresentation later comes to light, the insurer\'s response depends on how serious it was and what the insurer would have done had it known. That can mean a claim being paid in reduced form, terms being altered retrospectively, or in the most serious cases a claim being refused and the policy voided.</p>

<p>Practical steps that reduce the risk: check dates and details against repeat prescriptions or your GP record before you submit, describe conditions using the words your medical notes use, and if you are unsure whether something is relevant, disclose it and let the underwriter decide. Never let anyone else complete a health question on your behalf without you reading and confirming the answer.</p>

<h2>How different products treat health differently</h2>

<p>A condition that has little effect on a life insurance application can matter considerably more on critical illness cover or income protection, because those products pay out on events that are far more likely to be linked to existing health. A musculoskeletal or mental health history, for example, often has a much larger bearing on income protection than on life cover, since those are common reasons for long absences from work.</p>

<p>The reverse is also true. Some conditions affect mortality risk but not the specific illnesses listed in a critical illness policy. It is worth understanding which product you are actually being assessed for, because the outcome on one tells you little about the outcome on another.</p>

<h2>If you have already been declined or rated elsewhere</h2>

<p>A previous decision does not bind another insurer, but you will normally be asked about it, and you should answer accurately. What is worth doing is understanding why the decision was made. Underwriters will usually explain the reasoning to an adviser, and often the issue is something specific: missing test results, a recent change in medication, a condition that had not yet stabilised, or an application submitted too soon after an event.</p>

<p>Where a decision was based on timing rather than the condition itself, a later application may be looked at differently. Where a rating was applied, some insurers will reconsider terms after a defined period if circumstances have genuinely changed, though this is not automatic and is not offered by every insurer.</p>

<h2>Practical points that catch people out</h2>

<ul class="na-checklist">
<li>Never cancel existing cover until the new policy is in force and you have accepted the terms in writing.</li>
<li>Terms offered at the start of an application can change once medical evidence arrives, so treat an initial quotation as indicative.</li>
<li>If your health changes between applying and the policy starting, tell the insurer. There is usually a duty to do so, and it is far better addressed before cover begins.</li>
<li>Ask whether the policy is on guaranteed or reviewable premiums, as this determines whether the price can change later.</li>
<li>Consider whether the policy should be written in trust, which affects who receives the money and how quickly, and is separate from the underwriting question.</li>
</ul>

<h2>Trade-offs worth thinking through</h2>

<p>If the terms offered are higher than you hoped, the decision is rarely simply accept or walk away. You can look at whether a different sum assured or term changes the picture, whether a different product structure meets the same need, whether accepting an exclusion is reasonable given what the cover is for, or whether waiting for a condition to stabilise is sensible. Each of those involves a trade-off, and the right balance depends on your circumstances, your budget and what the cover is meant to protect.</p>

<p>What is rarely sensible is leaving a known need unprotected indefinitely while hoping for better terms later. Age and health both tend to move in one direction.</p>

<h2>What an adviser will want to understand</h2>

<ul class="na-checklist">
<li>The condition itself, when it was diagnosed, and how it has changed since.</li>
<li>Current and past medication, including anything stopped recently and why.</li>
<li>Any hospital referrals, investigations, tests or procedures, and their outcomes.</li>
<li>Whether you are currently under investigation or waiting for results.</li>
<li>Your height, weight, smoking or nicotine history and alcohol intake, as these interact with medical conditions during underwriting.</li>
<li>Family medical history where a close relative has had a serious condition.</li>
<li>Any previous application that was rated, postponed, declined or had an exclusion applied, and what the insurer said.</li>
<li>What the cover is actually for, such as a mortgage, family income or business commitment, and the term over which it is needed.</li>
<li>Existing cover already in place, including anything through an employer.</li>
</ul>

<p class="na-disclaimer">This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.</p>');
    }

    public static function guide_missed_premium_payments(): string
    {
        return self::html('<nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a> <span aria-hidden="true">/</span> <a href="/guides/">Guides</a> <span aria-hidden="true">/</span> <span>Missed premiums</span></nav>

<p class="na-eyebrow">Keeping cover</p>

<p class="na-lede">A missed premium is not usually the end of a policy, but it does start a clock. What happens next depends on your insurer, your policy wording, and how quickly you deal with it.</p>

<h2>The first thing to know</h2>

<p>Protection policies are not normally cancelled the instant a payment fails. Most contracts allow a period of grace during which the policy continues while the premium is outstanding, and insurers generally try to collect again before taking any further step. The length of that period, and what happens inside it, is set out in your policy conditions and varies between insurers.</p>

<p>What you should not do is assume it will sort itself out. The grace period is finite, letters go to the address the insurer holds, and a lapsed protection policy can be far harder to replace than it was to buy.</p>

<h2>What usually happens, in order</h2>

<ol>
<li>The payment fails. Your bank may show a returned direct debit or a declined card payment.</li>
<li>The insurer contacts you, normally in writing, and often attempts to collect again, sometimes alongside the next scheduled payment.</li>
<li>If the premium remains unpaid, the insurer issues arrears reminders and sets out what must be paid, and by when, to keep the policy running.</li>
<li>If nothing is resolved within the period allowed, the policy lapses. Cover ends from the date the contract specifies, which may be earlier than the date you receive the letter.</li>
<li>After a lapse, some insurers allow reinstatement within a stated window, usually on conditions. Beyond that window, the only route is a fresh application.</li>
</ol>

<p>The detail at every one of these stages is governed by the individual policy wording and the insurer\'s own process, so your documents, not a general description, are the authority on your policy.</p>

<h2>What happens to a claim while a premium is outstanding</h2>

<p>This is the part that matters most and the part people rarely ask about in advance. If a claim event happens while premiums are unpaid, the outcome depends on where in the process you are.</p>

<div class="na-callout-grid">
<div class="na-callout">
<h3>Inside the grace period</h3>
<p>Cover typically continues, and an insurer will commonly settle a valid claim while deducting any premium outstanding. Whether it does so, and on what basis, is set by the policy terms.</p>
</div>
<div class="na-callout">
<h3>After a lapse</h3>
<p>There is no cover. A claim for an event occurring after the lapse date is not payable, however recently the policy ended and however many years of premiums were paid before it.</p>
</div>
<div class="na-callout">
<h3>On a unit-linked plan</h3>
<p>Where premiums buy units and charges are deducted from a fund, an existing fund may sustain cover for a period. That is not a safety net to rely on, and it depletes.</p>
</div>
<div class="na-callout">
<h3>After reinstatement</h3>
<p>Reinstated cover may be subject to new conditions, and an insurer may not accept liability for anything that arose during the gap. Confirm in writing exactly what has been reinstated.</p>
</div>
</div>

<h2>Why premiums fail when nobody meant them to</h2>

<p>Most missed premiums are administrative rather than deliberate. The common causes are worth checking against your own arrangements.</p>

<ul>
<li>A bank account was closed or switched, and the direct debit did not transfer cleanly.</li>
<li>A card on file expired, was reissued after fraud, or was replaced when the bank changed provider.</li>
<li>The collection amount increased, because of indexation or a premium review, and the payment exceeded a limit or the balance available.</li>
<li>A joint account changed following separation, bereavement or a house move.</li>
<li>Money was tight in a particular month and the payment was returned unpaid.</li>
<li>The insurer\'s letters went to an old address, so the arrears process ran without the policyholder ever seeing it.</li>
<li>The payer was not the policyholder, for example a parent or a business paying on someone\'s behalf, and stopped without telling them.</li>
</ul>

<p>Keeping your address, bank details and contact preferences current with each insurer is unglamorous and genuinely protective.</p>

<h2>If you are struggling to afford the premium</h2>

<p>Cancelling is not the only option, and it is usually the most irreversible one. Before stopping anything, it is worth asking the insurer or your adviser what can be adjusted. Depending on the insurer and the individual policy terms, possibilities can include:</p>

<ul>
<li>Reducing the sum assured so the premium falls while some cover remains.</li>
<li>Shortening or restructuring the term, where the policy allows it.</li>
<li>Removing an additional benefit, such as critical illness cover or an optional extra, from a combined policy.</li>
<li>Stopping indexation, so the cover and the premium no longer rise each year.</li>
<li>Changing the payment date or frequency to fit when money actually arrives.</li>
<li>A short premium holiday or payment arrangement, where the insurer offers one.</li>
</ul>

<p>Insurers are expected to support customers in financial difficulty and customers in vulnerable circumstances. Telling them early gives them something to work with. Telling them after a lapse gives them far less.</p>

<p>Which of these options is appropriate, or whether any is, depends on your circumstances and on what the policy was protecting. Reducing cover has consequences, and they should be understood before the change is made rather than discovered at claim.</p>

<h2>Check whether waiver of premium applies</h2>

<p>If you are missing payments because illness or injury has stopped you working, look at whether your policy includes waiver of premium. Where it is included, and where the claim meets the policy definition, the insurer pays the premiums for you while you are unable to work, after a waiting period. It is an option that is frequently added at outset and then forgotten. Whether you have it, and what it covers, is stated in your policy schedule.</p>

<h2>Reinstatement is possible, but it is not a right</h2>

<p>Many insurers will consider putting a lapsed policy back in force. What they ask for depends on how long it has been lapsed and on their own rules. Commonly this involves paying the arrears, completing a declaration of health, and in some cases full medical underwriting again.</p>

<p>That last point is the trap. If your health has changed since the policy started, and for many people it will have, re-underwriting can produce an increased premium, an exclusion, or a refusal. The policy you had at your original terms may simply not be available any more. This is the same risk that makes cancelling cover before a replacement is in force so dangerous, and it applies just as much to a policy that lapsed by accident.</p>

<h2>What to do this week if a payment has failed</h2>

<ul class="na-checklist">
<li>Contact the insurer directly and establish the exact position: what is owed, from which date, and by when it must be paid.</li>
<li>Read the premium and lapse sections of your policy conditions so you know the grace period that applies to you.</li>
<li>Pay the arrears if you can, and get written confirmation that the policy remains in force.</li>
<li>Check whether the collection amount has changed, and why, before setting up the payment again.</li>
<li>Move the direct debit to an account that is active and reliably funded on that date.</li>
<li>Update your address and contact details with every insurer you hold a policy with, not just this one.</li>
<li>If the policy is in trust or assigned to a lender, tell the trustees or the lender what has happened.</li>
<li>If affordability is the real issue, speak to the insurer or an adviser about restructuring before the policy lapses rather than afterwards.</li>
</ul>

<h2>What an adviser will want to understand</h2>

<ul class="na-checklist">
<li>Which policy or policies are affected, with the insurer name and policy number, and the date the payment failed.</li>
<li>Whether the policy is still within its grace period, has lapsed, or is already in an arrears process.</li>
<li>Why the payment failed, and whether it is a one-off administrative problem or an ongoing affordability issue.</li>
<li>What the cover was originally arranged to protect, and whether that need still exists.</li>
<li>What other protection you hold, including anything provided by an employer, so the overall position is clear.</li>
<li>Any change in your health since the policy started, which affects whether replacing it is realistic.</li>
<li>What premium you could genuinely sustain going forward, honestly rather than optimistically.</li>
<li>Whether the policy is in trust, jointly held, or connected to a mortgage or business arrangement.</li>
</ul>

<p class="na-disclaimer">This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.</p>');
    }

    public static function guide_own_occupation_vs_any_occupation(): string
    {
        return self::html('<nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a> <span aria-hidden="true">/</span> <a href="/guides/">Guides</a> <span aria-hidden="true">/</span> <span>Occupation definitions</span></nav>

<p class="na-eyebrow">Income protection</p>

<p class="na-lede">The incapacity definition decides whether a claim is paid, and it is the single most important clause in an income protection policy. This guide explains the main definitions, why insurers offer different ones to different people, and where claims come unstuck.</p>

<h2>Why the definition matters more than the benefit amount</h2>

<p>Two policies can pay the same monthly benefit, cost roughly the same and look identical on a comparison table, yet behave completely differently at claim. The reason is the incapacity definition: the test the insurer applies to decide whether you count as unable to work.</p>

<p>A generous definition asks whether you can do your own job. A strict one asks whether you can do any job at all. Between those two sit several intermediate tests. The wording that applies to you is set out in your policy documents and is fixed by what you were offered at underwriting, not by what you assumed.</p>

<h2>The main definitions</h2>

<div class="na-callout-grid">
<div class="na-callout"><h3>Own occupation</h3><p>You are assessed against the specific job you were doing immediately before the claim. If illness or injury stops you performing your own role, the claim can be paid even if you could do other work.</p></div>
<div class="na-callout"><h3>Suited occupation</h3><p>You are assessed against your own job and any other occupation the insurer considers suited to your education, training and experience. A claim can be declined if such a role is judged available to you.</p></div>
<div class="na-callout"><h3>Any occupation</h3><p>The test is whether you are unable to carry out any paid work at all. This is a demanding standard and claims are correspondingly harder to satisfy.</p></div>
<div class="na-callout"><h3>Work tasks or activities</h3><p>Instead of referring to a job, the policy lists functional tasks such as walking, lifting, bending, communicating or manual dexterity. Inability to perform a set number of them triggers the claim.</p></div>
</div>

<h3>Own occupation in practice</h3>

<p>Own occupation is the definition most people have in mind when they buy income protection. Its value shows in specialised roles. A surgeon with a hand tremor, a lorry driver who loses their licence on medical grounds, a musician with hearing damage: each may be perfectly capable of some other work while being unable to continue in the career they trained for. Under own occupation that is a payable claim. Under any occupation it very probably is not.</p>

<p>The wording still deserves reading. Some policies define your occupation by the specific duties you performed, others by the job title, and some by a broader trade or profession. Where the definition is drawn narrowly it is more favourable to you. Insurers also generally require that you are not working in that occupation and are following medical advice.</p>

<h3>Suited occupation, the one that surprises people</h3>

<p>Suited occupation sounds close to own occupation but sits meaningfully further away. The insurer can look at your qualifications, your career history and your transferable skills, and consider whether another role exists that you could reasonably do. There is often no requirement that the role is actually offered to you, that it pays the same, or that it is available where you live, though this varies by wording.</p>

<p>The practical effect is that a claim can be assessed on what you could theoretically do rather than what you are actually doing. That is a very different conversation to have while unwell.</p>

<h3>Activities of daily working and functional tests</h3>

<p>Where a job cannot be assessed conventionally, insurers may use a functional test. This is common for applicants who are not in paid employment, who work irregular hours, or who are in occupations the insurer will not underwrite on an own occupation basis. It also appears in some household or homemaker cover.</p>

<p>Functional definitions are objective and easy to evidence, which is their advantage. The drawback is that many genuine work-stopping conditions, particularly mental health conditions and chronic pain, do not neatly prevent a listed physical activity, so the threshold can be hard to reach even when returning to work is out of the question.</p>

<h2>Why you may not be offered the definition you want</h2>

<p>Own occupation is not universally available. Insurers group occupations into classes based on the nature of the work, the physical risk involved and how easily a claim can be assessed. Manual trades, roles involving heights or heavy machinery, some armed forces and emergency service roles, and certain sports and entertainment occupations are commonly placed in classes where own occupation is restricted or unavailable. The same job can also be classified differently by different insurers, which is why terms are worth comparing rather than assuming.</p>

<p>Occupation class also affects the maximum benefit period, the deferred periods on offer and the premium. Where own occupation is not available at all, the choice is usually between a suited occupation basis, a functional test, or short-term cover.</p>

<h2>Definitions that change during a claim</h2>

<p>Some policies do not apply one definition throughout. A policy may assess the first period of a claim on an own occupation basis and then switch to a suited or any occupation basis after a set time. Group schemes arranged by employers frequently work this way, and it is easy to miss because the headline description of the scheme mentions only the first definition.</p>

<p>If a switch applies, the important question is what happens at the switch point: whether benefit stops, whether the insurer must show a suitable role exists, and what evidence is required. This depends entirely on the individual policy wording.</p>

<h2>Where things go wrong</h2>

<ul>
<li>Assuming an employer\'s group income protection uses own occupation throughout. Many do not, and many switch definition partway through a claim.</li>
<li>Changing career after the policy starts. Most policies assess against the occupation you were doing immediately before the claim, so a move into higher-risk work can matter. Some policies also ask you to notify a change of occupation. Check whether yours does.</li>
<li>Reading "own occupation" on a marketing page and never checking the wording, where the definition may be tied to a broader trade rather than your specific duties.</li>
<li>Buying on price alone and ending up with a functional test without realising it.</li>
<li>Overlooking that any definition still requires medical evidence, adherence to treatment and, usually, that you are not earning from that occupation.</li>
<li>Forgetting that critical illness cover and life cover use their own separate definitions, so a total permanent disability benefit on a critical illness policy may be assessed on an any occupation basis even where your income protection is not.</li>
</ul>

<h2>Part-time work, rehabilitation and returning gradually</h2>

<p>Most modern income protection includes some form of proportionate or rehabilitation benefit, which allows a reduced payment if you go back to work in a limited capacity or on lower earnings. This interacts directly with the incapacity definition, because the policy has to decide how partial incapacity is measured, usually by comparing your earnings before and after. The calculation, the maximum period and whether a linked claim can be reopened without a new deferred period all vary by insurer.</p>

<h2>What an adviser will want to understand</h2>

<ul class="na-checklist">
<li>Your exact job title and, more importantly, what you actually do day to day.</li>
<li>Whether the role is manual, involves travel, heights, driving or specialist licensing.</li>
<li>Any professional registration or licence that your ability to work depends on.</li>
<li>Whether you expect your occupation or duties to change in the next few years.</li>
<li>What incapacity definition any employer scheme uses, and whether it switches during a claim.</li>
<li>Your medical history, since it affects both terms and the exclusions applied.</li>
<li>How you would cope financially with a partial return to work on reduced hours.</li>
<li>Which matters more to you: the strongest available definition, or the lowest premium.</li>
<li>Whether you also hold critical illness or life cover, and what definitions those use.</li>
</ul>

<p class="na-disclaimer">This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.</p>');
    }

    public static function guide_protection_when_self_employed(): string
    {
        return self::html('<nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a> <span aria-hidden="true">/</span> <a href="/guides/">Guides</a> <span aria-hidden="true">/</span> <span>Self-employed protection</span></nav>

<p class="na-eyebrow">Working for yourself</p>

<p class="na-lede">When you work for yourself, there is no employer sick pay, no death in service benefit and no HR department to sort it out. Protection has to do a job that an employed person\'s contract would otherwise do.</p>

<h2>The gap is wider than most people assume</h2>

<p>An employee who cannot work usually has something between them and nothing: contractual sick pay for a period, then Statutory Sick Pay, then whatever group income protection or death in service benefit the employer provides. Self-employed people have none of that. Statutory Sick Pay is a payment from an employer to an employee, so if you are a sole trader you are outside it entirely. If you run your own limited company and pay yourself a salary through PAYE, you are technically an employee of that company, which means the company is the one that would have to fund any sick pay, and the company usually depends on you being at work.</p>

<p>The state does provide contribution-based and means-tested benefits for people unable to work, but eligibility depends on your National Insurance record, your household circumstances, savings and other income. It is not designed to replace a working income, and it is not something to build a business or a mortgage around. Check your own entitlement on GOV.UK rather than assuming.</p>

<p>The other thing that goes with self-employment is that the business stops when you do. An employee who is off sick still has an employer generating revenue. A sole trader off sick has no invoices going out, and often still has costs going out.</p>

<h2>Income protection is usually the first conversation</h2>

<p>Income protection pays a monthly benefit if illness or injury prevents you working, after a chosen waiting period, and continues until you recover, until the policy\'s end date, or until a maximum payment period, depending on the type of policy you hold. For self-employed people it is normally the most directly relevant product, because loss of earning ability is the risk that has no fallback.</p>

<p>Several choices inside it matter more than the headline premium.</p>

<div class="na-callout-grid">
<div class="na-callout">
<h3>Deferred period</h3>
<p>How long you wait before benefit starts. A longer wait generally reduces the premium, but you must be able to fund that period from savings or business reserves. Choose it against what you actually have, not what feels affordable.</p>
</div>
<div class="na-callout">
<h3>Occupation definition</h3>
<p>Own occupation policies assess you against your own job. Suited occupation and any occupation definitions are broader, and can mean no benefit if you could do some other work. The exact wording is set by the insurer.</p>
</div>
<div class="na-callout">
<h3>Payment term</h3>
<p>Short-term policies limit each claim to a set period. Full-term policies can pay until the end date if the claim continues. This is one of the biggest differences between two policies that look similar in price.</p>
</div>
<div class="na-callout">
<h3>Premium basis</h3>
<p>Guaranteed premiums are fixed at outset. Reviewable or age-costed premiums start lower and can increase. Which suits you depends on how long you expect to hold the cover and your tolerance for future increases.</p>
</div>
</div>

<h2>How insurers define your income</h2>

<p>This is where self-employed applications differ most from employed ones, and where problems appear at claim.</p>

<p>For a sole trader or partner, insurers typically look at your share of the net profit of the business, before tax, rather than the money you draw. For a limited company director, the definition often combines salary with dividends drawn from the company, and some insurers will also consider your share of retained profit. Definitions vary between insurers, and a policy\'s own definition is what governs any claim.</p>

<p>Benefit is capped at a proportion of your earnings, set by the insurer, so that a claim does not leave you better off than working. The consequences for the self-employed are practical:</p>

<ul>
<li>If your income fluctuates, insurers often average it over a period, so a strong recent year may not be the figure used.</li>
<li>If you have recently started trading, some insurers want a minimum period of accounts before they will cover you, and how long varies.</li>
<li>If you legitimately minimise taxable profit, the income the insurer recognises may be lower than the income you feel you earn.</li>
<li>Your income at the time of claim, not at the time of application, usually determines what is paid.</li>
</ul>

<p>Keeping accounts, tax calculations and tax year overviews in order is not administrative tidiness, it is claim evidence. Whether a particular figure counts is decided by the individual policy wording and the insurer\'s assessment of your circumstances.</p>

<h2>Cover for the business, not just for you</h2>

<p>Personal cover replaces your income. It does not keep the business standing. Depending on how you trade, there are separate arrangements to consider.</p>

<ul>
<li>Business expenses cover. Pays towards fixed running costs such as premises, equipment leases and certain staff costs while you are unable to work, so that the business does not consume your personal benefit.</li>
<li>Key person cover. Taken out by a business on the life or health of someone whose loss would damage profits, with the business as beneficiary.</li>
<li>Shareholder or partnership protection. Provides funds for the surviving owners to buy the departing owner\'s share, usually alongside a cross-option agreement, so the family receive value and the survivors keep control.</li>
<li>Business loan protection. Covers borrowing that would otherwise fall due, including any director\'s loan account or personal guarantee.</li>
<li>Relevant life plans. A death-in-service style policy that a limited company can arrange for an individual employee or director. Not available to sole traders, because there is no separate employer. Tax treatment depends on the arrangement being set up correctly and on HMRC practice, and can change.</li>
</ul>

<p>Anything involving a company or a partnership needs the legal documents to match the policy. A shareholder protection policy without a corresponding agreement, or an agreement drafted so that a binding obligation to buy exists, can produce results nobody intended. This is territory where accountancy and legal advice sit alongside the protection advice.</p>

<h2>What commonly goes wrong</h2>

<ul class="na-checklist">
<li>Cover was bought on the income the applicant thought they earned, rather than the income the insurer\'s definition recognises, leaving a shortfall at claim.</li>
<li>A deferred period was chosen for price, with no savings actually capable of bridging it.</li>
<li>Only life cover was arranged, when the more probable disruption is a period of illness rather than death.</li>
<li>A mortgage was granted on the strength of trading income, with no cover behind that income at all.</li>
<li>Business and personal needs were mixed into one personal policy, so a claim has to fund both the household and the business.</li>
<li>Premiums were paid from a business account without checking whether that was appropriate for the type of policy and how it would be treated.</li>
<li>Changes to the business, incorporating, taking on partners, changing trade, were never reported, and the cover no longer reflects reality.</li>
</ul>

<h2>Who pays for it</h2>

<p>Whether a policy can or should be paid by the business, and how premiums and benefits are treated for tax, depends on the type of policy, how you trade and your own circumstances. Some arrangements are designed to be company funded, others are personal by nature. Assumptions here are expensive to unwind, so it is a question for your accountant alongside your adviser rather than something to decide from an online summary.</p>

<h2>What an adviser will want to understand</h2>

<ul class="na-checklist">
<li>How you trade: sole trader, partnership, or limited company, and how long you have been trading.</li>
<li>Your income as it appears in accounts and tax documents, not just what you take out each month, and how much it varies.</li>
<li>How long the business could survive without you, and what fixed costs continue regardless.</li>
<li>What savings or reserves you could genuinely live on, and for how long, before benefit needs to start.</li>
<li>Any borrowing in the business, including personal guarantees and director\'s loans, and any mortgage on your home.</li>
<li>Who else depends on the business, including a spouse working in it or employees whose jobs rely on you.</li>
<li>Any existing cover, including anything arranged when you were previously employed, and whether it still fits.</li>
<li>Your full medical history and the physical demands of the work you actually do, which affects how insurers class your occupation.</li>
</ul>

<p class="na-disclaimer">This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.</p>');
    }

    public static function guide_smoking_vaping_and_life_insurance(): string
    {
        return self::html('<nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a> <span aria-hidden="true">/</span> <a href="/guides/">Guides</a> <span aria-hidden="true">/</span> <span>Smoking and vaping</span></nav>

<p class="na-eyebrow">Lifestyle and cover</p>

<p class="na-lede">Insurers price protection cover differently for people who use nicotine, and the definitions are wider than most applicants assume. This guide explains how the question is asked, what counts, and what happens if you give up.</p>

<h2>How insurers ask the question</h2>

<p>Application forms do not usually ask whether you consider yourself a smoker. They ask whether you have used tobacco or nicotine products within a defined recent period, and they list what they mean. The length of that period is set by each insurer and differs between them, so the same person can be classed differently by two providers on the same day.</p>

<p>Because the wording varies, read the actual question rather than answering from habit. Some insurers ask a single combined question, others separate tobacco from nicotine replacement, and a few ask about frequency or quantity. Answering the question in front of you, exactly as written, is what matters.</p>

<h2>What usually counts as nicotine use</h2>

<p>The list is broader than cigarettes. Depending on the insurer, the question commonly captures:</p>

<ul>
<li>Cigarettes, including roll-ups and social or occasional use.</li>
<li>Cigars and pipe tobacco.</li>
<li>Shisha or waterpipe tobacco.</li>
<li>Chewing tobacco, snus and other oral tobacco products.</li>
<li>Heated tobacco devices.</li>
<li>E-cigarettes and vapes, including nicotine-free liquids under some wordings.</li>
<li>Nicotine replacement therapy such as patches, gum, lozenges, sprays and inhalators.</li>
</ul>

<p>The inclusion of nicotine replacement therapy surprises people most. Someone who has stopped smoking and is using patches or gum to stay stopped is still using nicotine, and many insurers class them accordingly. That does not make giving up the wrong decision, and it is not permanent, but it does mean the timing of an application matters.</p>

<p>Cannabis and other recreational drug use is normally covered by separate questions, and is assessed on its own terms rather than as part of the smoking question. Where a substance is smoked with tobacco, both questions may be relevant.</p>

<h2>How vaping is treated</h2>

<p>There is no single industry position on vaping. Most UK insurers currently treat vaping as nicotine use and apply their nicotine-user rates. Some distinguish between vaping and smoking tobacco in certain circumstances, and treatment continues to develop as underwriting practice changes.</p>

<p>What this means in practice is that you should never assume vaping is treated as equivalent to not smoking, and never assume it is treated identically to cigarettes. It depends on the insurer, the product and the wording in force at the time you apply.</p>

<p>One further point that is often missed: a policy on nicotine-user terms is not somehow inferior. It pays out in the same circumstances and under the same wording as any other policy. The difference sits in the premium, not in the protection.</p>

<h2>Why it is not worth understating</h2>

<p>Nicotine use is one of the few lifestyle answers that can be checked directly. Where an insurer arranges a medical screening, a urine or blood sample can be tested for cotinine, a marker of nicotine exposure. Insurers may also test at the point of a claim where it is relevant to do so.</p>

<p>Under the Consumer Insurance (Disclosure and Representations) Act 2012 you must take reasonable care not to make a misrepresentation when applying. If nicotine use was understated and this comes to light, the insurer\'s response depends on how serious the misrepresentation was and what it would have done had it known. That can mean a claim being reduced in proportion, the policy being treated as though different terms applied, or in serious cases a claim being refused and the policy voided.</p>

<p>The practical risk is not an abstract one. It falls at exactly the moment your family is relying on the money, and it is entirely avoidable by answering the question accurately at the outset.</p>

<h2>If you give up</h2>

<p>Stopping is not automatically reflected in an existing policy. Most policies do not adjust the premium simply because your circumstances have improved. The usual route is either to ask your current insurer whether it will reconsider the terms, where that option exists, or to apply for a new policy on non-smoker terms.</p>

<p>Several things follow from that, and they are worth thinking through together rather than in isolation.</p>

<div class="na-callout-grid">
<div class="na-callout"><h3>You must clear the declared period</h3><p>An insurer\'s non-smoker question asks about a defined period of nicotine-free time. Applying before that period has passed means answering the question as a nicotine user.</p></div>
<div class="na-callout"><h3>A new policy means new underwriting</h3><p>You will be older, and you will answer current health questions again. Anything that has happened since the original policy started becomes part of the new assessment.</p></div>
<div class="na-callout"><h3>Do not cancel first</h3><p>Existing cover should stay in force until the replacement policy has been accepted, put on risk and the terms confirmed in writing.</p></div>
<div class="na-callout"><h3>Compare like for like</h3><p>Term remaining, sum assured, whether premiums are guaranteed or reviewable, and any trust arrangement all need to carry across, not just the price.</p></div>
</div>

<p>Whether replacing a policy is sensible depends on your health, your age, the cover you already hold and what has changed since it started. It is not automatically the right move, and it can occasionally be the wrong one, which is why it is worth talking through before anything is cancelled.</p>

<h2>Does it affect every product</h2>

<p>Nicotine use is relevant across life cover, critical illness cover and income protection, but the weight it carries differs. It has an obvious bearing on life cover and on several conditions covered by critical illness policies. Its effect on income protection depends more on the insurer\'s approach and on the rest of your health and occupation.</p>

<p>Some products and some insurers also apply nicotine status to related benefits within a plan, so a single answer can influence more than one part of the cover. Where a policy covers two people, each person is assessed on their own answers.</p>

<h2>Common misunderstandings</h2>

<ul class="na-checklist">
<li>Assuming occasional or social smoking does not count. Most wordings capture any use within the defined period.</li>
<li>Assuming nicotine replacement therapy counts as having stopped. Many insurers treat it as ongoing nicotine use.</li>
<li>Assuming vaping is automatically treated as non-smoking. Treatment varies by insurer.</li>
<li>Assuming a policy updates itself when you give up. It generally does not without action.</li>
<li>Assuming all insurers use the same nicotine-free period. They do not.</li>
<li>Cancelling old cover before new cover is confirmed in force.</li>
</ul>

<h2>What an adviser will want to understand</h2>

<ul class="na-checklist">
<li>What you use or have used, including cigarettes, cigars, shisha, oral tobacco, heated tobacco, vapes and nicotine replacement products.</li>
<li>When you last used any of them, as precisely as you can.</li>
<li>Whether you are currently trying to stop, and what you are using to do so.</li>
<li>Your wider health picture, since nicotine status interacts with other conditions during underwriting.</li>
<li>Any existing protection policies, when they started, and the terms they were issued on.</li>
<li>Whether existing cover is on guaranteed or reviewable premiums.</li>
<li>What the cover needs to do, over what period, and for whom.</li>
<li>Whether any existing policy is written in trust or assigned.</li>
</ul>

<p class="na-disclaimer">This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.</p>');
    }

    public static function guide_switching_or_cancelling_protection(): string
    {
        return self::html('<nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a> <span aria-hidden="true">/</span> <a href="/guides/">Guides</a> <span aria-hidden="true">/</span> <span>Switching or cancelling</span></nav>

<p class="na-eyebrow">Existing policies</p>

<p class="na-lede">Changing a protection policy is not like changing a car insurer. There is one sequencing mistake that can leave a family with nothing, and it happens to people who thought they were being sensible.</p>

<h2>Never cancel existing cover until the replacement is in force</h2>

<p>This is the single most important point on this page. If you are thinking about replacing a life, critical illness or income protection policy, keep the existing one running and paid until the new policy has actually started. Not applied for. Not provisionally accepted. Started.</p>

<p>The reason is simple and unforgiving. Between the day you apply and the day cover begins, several things can happen:</p>

<ul>
<li>The insurer requests a GP report, nurse screening or further tests, and the terms first indicated change as a result.</li>
<li>The application is rated, an exclusion is applied, or it is postponed or declined outright.</li>
<li>Your health changes. A symptom appears, a test is booked, a referral is made, a diagnosis lands, or you are prescribed something new.</li>
<li>An accident happens. Nothing about the timing of an accident respects your paperwork.</li>
</ul>

<p>Any health change occurring between application and the policy starting normally has to be disclosed, and most insurers require confirmation that nothing has changed before cover begins. A new symptom at that point can mean revised terms, a delay, or no cover at all. If you have already cancelled the old policy, you cannot simply reverse it. Insurers are under no obligation to reinstate a cancelled policy, and if they do consider it, they can require fresh health information, which is exactly the information that has just changed.</p>

<p>The worst version of this is the person who cancels a decades-old policy to save money on a new one, is diagnosed with something during the application, and ends up uninsurable with no cover at all. That is a real and avoidable outcome. Overlapping cover for a few weeks costs a little. Getting the order wrong can cost everything.</p>

<h2>What "in force" actually means</h2>

<p>Treat the old policy as untouchable until every one of these is true.</p>

<ol>
<li>The insurer has issued a final acceptance, with any rating, exclusion or special terms clearly set out and accepted by you.</li>
<li>There are no outstanding requirements, such as a GP report, medical examination or additional evidence.</li>
<li>A policy start date has been confirmed in writing and that date has arrived.</li>
<li>The first premium has been collected, or the insurer has confirmed cover is on risk.</li>
<li>You have read the policy schedule and the new cover genuinely matches what you intended, including sum assured, term, benefits and any exclusions.</li>
</ol>

<p>Only then should the old policy be cancelled, and it should be cancelled properly, through the insurer, rather than by stopping the direct debit.</p>

<h2>What you may be giving up</h2>

<p>Old policies are often better than they look on a price comparison. Before replacing one, work out what is embedded in it.</p>

<div class="na-callout-grid">
<div class="na-callout">
<h3>Your age and health at the time</h3>
<p>The policy was priced on who you were when it started. A new policy is priced on who you are now, with everything that has happened to your health since.</p>
</div>
<div class="na-callout">
<h3>Clean underwriting terms</h3>
<p>An existing policy accepted on standard terms carries no exclusions for conditions diagnosed after it started. A new application can exclude them.</p>
</div>
<div class="na-callout">
<h3>Guarantees and options</h3>
<p>Guaranteed premiums, guaranteed insurability options, waiver of premium and conversion options can all disappear on replacement. Check the original documents, not memory.</p>
</div>
<div class="na-callout">
<h3>Waiting periods already served</h3>
<p>Some benefits and plan types apply an initial period during which cover is limited. Starting again can mean serving that period again.</p>
</div>
</div>

<p>The opposite can also be true. Critical illness definitions and the range of conditions covered have changed over time, and some older contracts are narrower than current ones. Whether an old policy or a new one is better for you depends on comparing the actual wordings, and on your circumstances, rather than on assuming newer or older is automatically superior.</p>

<h2>Reasons switching can be the right answer</h2>

<p>None of the above means never change anything. There are sound reasons to move.</p>

<ul>
<li>The cover no longer matches the need, for example a decreasing policy set against a mortgage that has been remortgaged onto a longer term, or level cover for a debt that has been repaid.</li>
<li>The premium is reviewable and a review has produced an increase that is not sustainable.</li>
<li>The policy was written to cover something that no longer exists, and a different structure fits better.</li>
<li>The benefits are materially narrower than what is now available, and your health still allows a fresh application.</li>
<li>The policy is not in trust and restructuring it would get the money to the right people more quickly.</li>
</ul>

<p>What matters is that the comparison is made on the wordings and on your current position, and that the replacement is secured before the original is released.</p>

<h2>Alternatives to cancelling</h2>

<p>If the driver is cost, cancellation is rarely the only lever. Depending on the insurer and the individual policy terms, you may be able to reduce the sum assured, shorten the term, remove an added benefit such as critical illness or waiver, change the indexation, or adjust the payment date. Some insurers offer a limited premium holiday or a period during which a lapsed policy can be reinstated without full re-underwriting. These are all at the insurer\'s discretion and set out in the policy conditions.</p>

<p>Reducing cover you keep is almost always safer than surrendering cover you cannot get back.</p>

<h2>Cancelling altogether</h2>

<p>Sometimes the need really has ended and there is no replacement. The mortgage is repaid, the children are independent, the business interest has been sold. Even then, check a few things first.</p>

<ul>
<li>Is the policy doing a second job you have forgotten, such as covering funeral costs, an inheritance tax exposure, or a dependant\'s ongoing needs?</li>
<li>Is it a joint policy, and does the other person agree and understand the effect on them?</li>
<li>Is it written in trust? If so, the trustees are the legal owners and their involvement is required. You cannot simply cancel it yourself.</li>
<li>Is it assigned to a lender, or referenced in a partnership or shareholder agreement?</li>
<li>Does it have any surrender value, or none at all, which is common on protection contracts?</li>
</ul>

<p>Policies also carry a statutory cancellation period at the very start, described in the documents you were sent. Cancelling within that window is different from cancelling an established policy, and the documents will say what applies.</p>

<h2>Cancel properly, not by stopping the payment</h2>

<p>Cancelling a direct debit is not the same as cancelling a policy. It usually results in a missed premium, then a lapse, and it can leave you without written confirmation of when cover ended. If a claim event happens during that muddle, the position is far less clear than it should be. Contact the insurer, confirm in writing, and keep the confirmation. If a policy is being replaced, keep the old documents too. They are evidence of what you had and when.</p>

<h2>What an adviser will want to understand</h2>

<ul class="na-checklist">
<li>The full details of the existing policy: insurer, start date, term, sum assured, premium type and any special terms applied at outset.</li>
<li>What prompted the review, whether that is cost, a premium increase, a change in circumstances or a marketing letter.</li>
<li>What the policy was originally taken out to cover, and whether that reason still exists.</li>
<li>Any change in your health, medication, investigations or family history since the original policy started.</li>
<li>Whether the policy is in trust, jointly held, or assigned to anyone.</li>
<li>Whether you have any other cover, personal or through an employer, that overlaps.</li>
<li>Your realistic budget, so that any alternative is one you can actually maintain.</li>
<li>Your timescales, particularly if a mortgage completion or other deadline is driving the decision.</li>
</ul>

<p class="na-disclaimer">This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.</p>');
    }

    public static function guide_waiver_of_premium_explained(): string
    {
        return self::html('<nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a> <span aria-hidden="true">/</span> <a href="/guides/">Guides</a> <span aria-hidden="true">/</span> <span>Waiver of premium</span></nav>

<p class="na-eyebrow">Policy features</p>

<p class="na-lede">Waiver of premium keeps a protection policy running when illness or injury stops you working. It is one of the least understood features on a UK protection plan, and one of the easiest to overlook until it is needed.</p>

<h2>What waiver of premium does</h2>

<p>Waiver of premium is a benefit that pays your policy premiums for you if you are unable to work because of illness or injury, once a waiting period has passed. The policy stays fully in force. If you then die or suffer a condition the policy covers, it pays out exactly as it would have done had you kept paying.</p>

<p>The value of it is easy to state. Long-term illness is the most likely reason someone stops being able to pay their protection premiums, and it is also the point at which the cover matters most. Without waiver, a policy can lapse during exactly the period it was bought to protect against, and reinstating cover afterwards means new underwriting on a health record that has just changed.</p>

<p>It is worth being precise about the limit of the benefit. Waiver of premium pays your premiums. It does not pay you an income, it does not cover your mortgage, and it does not put money in your bank account. It protects the policy, not the household budget.</p>

<h2>Where you find it</h2>

<p>Waiver can appear on life cover, critical illness cover, combined life and critical illness plans, family income benefit, whole of life policies and income protection. On some products and with some insurers it is included as standard. On others it is an optional benefit selected at application for an additional premium.</p>

<p>On income protection in particular, waiver is significant. Without it, you would be paying premiums for a policy while claiming on it, at the point your income has already fallen. Many income protection plans include waiver as standard for that reason, but not all do, and the terms differ.</p>

<h2>The parts of the wording that matter</h2>

<div class="na-callout-grid">
<div class="na-callout"><h3>The deferred period</h3><p>Premiums are not waived immediately. You must be unable to work for a defined waiting period first, and you continue paying during it. The length is set by the insurer or chosen at application.</p></div>
<div class="na-callout"><h3>The definition of incapacity</h3><p>Some insurers assess whether you can do your own occupation, others a suited occupation, and others use functional tests. Own occupation is generally the clearest to claim under.</p></div>
<div class="na-callout"><h3>When it stops</h3><p>The benefit ends when you are able to return to work as defined, at a ceasing age set by the insurer, or when the policy itself ends, whichever comes first.</p></div>
<div class="na-callout"><h3>Backdating</h3><p>Some policies refund premiums paid during the deferred period once a claim is accepted, and some do not. This varies and is worth checking.</p></div>
</div>

<p>The incapacity definition is the single most important element. Two policies can both advertise waiver of premium and behave very differently at claim because one assesses your actual job and the other assesses whether you could do any work you are reasonably suited to. Which applies depends on the insurer, the product and often your occupation.</p>

<h2>What it does not cover</h2>

<p>Waiver of premium responds to illness or injury. It is not unemployment cover and will not help if you lose your job, are made redundant or your business stops trading for commercial reasons. Redundancy protection, where available, is an entirely separate product with its own conditions.</p>

<p>Policies also carry exclusions in the same way the main cover does. Conditions excluded from the underlying policy may be excluded from the waiver benefit, self-inflicted injury and certain hazardous activities are commonly excluded, and pre-existing conditions may be treated separately. As always, the specific wording governs.</p>

<h2>How a claim works</h2>

<p>You notify the insurer, usually within a period specified in the policy, and provide medical evidence supporting your inability to work. The insurer assesses it against the incapacity definition. During the deferred period you keep paying premiums, and once the claim is accepted the insurer takes over from the point the wording specifies.</p>

<p>Insurers normally review ongoing claims periodically and may ask for updated medical evidence. When you return to work, the waiver stops and you resume paying. Some policies treat a recurrence of the same condition within a defined window as a continuation of the original claim rather than a new one, which can mean the deferred period does not have to be served again. This is a genuinely useful feature where it exists, and it is not universal.</p>

<h2>Where it commonly goes wrong</h2>

<ul class="na-checklist">
<li>Not knowing the benefit is on the policy, and letting cover lapse while off sick because premiums became unaffordable.</li>
<li>Missing the notification deadline in the policy wording after becoming unable to work.</li>
<li>Assuming waiver replaces income protection. It pays premiums, not earnings.</li>
<li>Assuming it covers redundancy or a downturn in self-employed work. It does not.</li>
<li>Not checking the incapacity definition, and discovering at claim that it assesses any suited occupation rather than your own job.</li>
<li>Cancelling a direct debit while off sick instead of contacting the insurer, which can end the policy altogether.</li>
<li>Declining the benefit at application to reduce the premium without weighing what it protects against.</li>
</ul>

<h2>Deciding whether it is worth having</h2>

<p>Waiver of premium adds to the cost of a policy where it is optional, so it is a genuine trade-off rather than a free extra. The way to think about it is to ask how long you could keep paying the premiums if your income stopped tomorrow, and what would happen to the cover if you could not.</p>

<p>That question tends to be answered differently by different households. Someone with substantial employer sick pay, meaningful savings and a short remaining policy term is in a different position from someone self-employed with no sick pay and a long term ahead of them. The relevant factors include your sick pay arrangements, your savings, whether anyone else in the household earns, the size of the premium, and how long the cover has left to run.</p>

<p>It is also worth considering waiver alongside income protection rather than instead of it. Income protection addresses the loss of earnings, and waiver keeps the protection arrangement itself intact while that is happening. They are complementary rather than alternatives, and the balance between them depends on budget and priorities.</p>

<h2>Self-employed and contractor considerations</h2>

<p>If you are self-employed, a company director or working on contract, two things deserve attention. First, there is usually no employer sick pay standing behind you, so the period during which premiums would have to come from savings can be long. Second, insurers define inability to work differently for people whose role is varied or whose income is drawn in different ways, and how your occupation and earnings are described at application can affect how a claim is assessed.</p>

<p>Getting the occupational description right at the outset is straightforward to do and difficult to fix afterwards, so it is worth spending time on.</p>

<h2>What an adviser will want to understand</h2>

<ul class="na-checklist">
<li>Your occupation, your actual day-to-day duties, and your employment status.</li>
<li>What sick pay you would receive from an employer, at what level and for how long.</li>
<li>How long your savings would cover essential outgoings and premiums if your income stopped.</li>
<li>Your existing protection policies, and whether any already include waiver of premium.</li>
<li>The deferred period and incapacity definition on any cover you already hold.</li>
<li>Whether you have income protection in place, and if so its deferred period and benefit level.</li>
<li>Your health history, since waiver is underwritten alongside the main cover.</li>
<li>Your budget, and how you would weigh the additional cost against the risk of a policy lapsing.</li>
<li>The term remaining on the cover and what it is protecting.</li>
</ul>

<p class="na-disclaimer">This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.</p>');
    }

    public static function guide_whole_of_life_insurance_explained(): string
    {
        return self::html('<nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a> <span aria-hidden="true">/</span> <a href="/guides/">Guides</a> <span aria-hidden="true">/</span> <span>Whole of life insurance</span></nav>

<p class="na-eyebrow">Types of cover</p>

<p class="na-lede">Whole of life insurance is designed to pay out whenever you die, rather than only if you die within a fixed period. That single difference changes how it is priced, how it is used, and what can go wrong with it.</p>

<h2>What whole of life insurance actually is</h2>

<p>Most life insurance sold in the UK is term assurance. You choose a length of time, say until the mortgage ends or the children finish education, and the policy pays out only if you die inside that window. Outlive the term and the cover simply ends, with nothing paid.</p>

<p>Whole of life cover has no end date. Provided the policy is still in force and premiums have been paid as required, a claim is payable whenever death occurs. The insurer is not betting on you surviving a fixed period; it is accepting that a claim will, in the ordinary course of events, happen at some point. That is the main reason whole of life premiums are typically higher than term premiums for the same sum assured and the same person.</p>

<p>Whole of life policies come in several shapes, and the differences matter far more than the shared label suggests.</p>

<div class="na-callout-grid">
<div class="na-callout">
<h3>Guaranteed premium plans</h3>
<p>The premium is fixed at outset for life and does not change, whatever happens to investment returns or the insurer\'s claims experience. The certainty is the point, and it is usually reflected in the starting price.</p>
</div>
<div class="na-callout">
<h3>Reviewable premium plans</h3>
<p>The premium is set for an initial period and then reviewed at intervals set out in the policy. Reviews can result in the premium rising, the cover falling, or a choice between the two.</p>
</div>
<div class="na-callout">
<h3>Unit-linked plans</h3>
<p>Premiums buy units in a fund and the cost of the life cover is deducted from that fund. If fund performance or charges do not work out as assumed, a review can require more premium to sustain the same sum assured.</p>
</div>
<div class="na-callout">
<h3>Over-50s guaranteed acceptance plans</h3>
<p>Sold without medical questions to people in a set age band, usually with a waiting period at the start during which only premiums paid, or a limited amount, are returned on death from natural causes.</p>
</div>
</div>

<h2>Why people buy it</h2>

<p>Term assurance answers a temporary problem: a debt that reduces, children who grow up, a working life that ends. Whole of life answers a permanent one. Common reasons include the following.</p>

<ul>
<li>Funeral and estate costs. A sum intended to meet funeral expenses and the administrative costs of winding up an estate, so that family are not funding them from their own money while probate is outstanding.</li>
<li>Inheritance tax liability. Where an estate is expected to face an inheritance tax charge, a whole of life policy written under an appropriate trust can provide funds to meet the bill, with the proceeds intended to sit outside the estate and be payable to the trustees. Whether a trust achieves this, and which trust is suitable, depends on your circumstances, and tax treatment can change.</li>
<li>Providing for a dependant with lifelong needs. Where someone will rely on you financially for their whole life, cover that expires on a fixed date does not match the need.</li>
<li>Business arrangements. Some shareholder and partnership agreements are structured around permanent rather than fixed-term cover.</li>
<li>Leaving a defined legacy. Some people want a specific sum to reach specific people, regardless of what happens to the rest of their estate.</li>
</ul>

<h2>The premium review is the part people misunderstand</h2>

<p>If your plan has reviewable premiums, the policy document will say when reviews happen, usually after an initial period and then at set intervals. At a review, the insurer reassesses whether the premium being paid, together with any fund value, is enough to sustain the sum assured for the rest of your life on its current assumptions.</p>

<p>If the answer is no, you are generally offered a choice: pay a higher premium to keep the same cover, or keep the same premium and accept a reduced sum assured. Neither is comfortable, and reviews tend to land at ages when income has fallen. The practical risk is a policy that becomes unaffordable in later life, is cancelled, and pays nothing after decades of premiums.</p>

<p>This is not a reason to avoid reviewable plans. It is a reason to know which type you hold, when the first review falls, and what the illustration assumed. The review basis, the timing and the options available are all set by the individual policy wording and the insurer.</p>

<h2>Maximum, balanced and minimum cover</h2>

<p>Unit-linked whole of life plans have historically been offered on different bases. A maximum cover basis buys the largest sum assured the premium can support, with little reserve building in the fund, so it is the most exposed at review. A balanced basis aims to sustain the cover for life on the insurer\'s assumptions. A minimum cover basis directs more premium into the fund, buying less cover initially but with more resilience.</p>

<p>These labels are not standardised, and older policies may use different terms. If you hold an existing plan, obtain a current review statement from the insurer rather than assuming which basis applies.</p>

<h2>What whole of life does not do</h2>

<p>It is life cover, not an investment. Even where a unit-linked plan holds a fund, that fund exists to support the cost of cover. Surrender values, where they exist at all, are frequently low relative to premiums paid, and some whole of life contracts have none at all. Cancelling generally means walking away with nothing.</p>

<p>It is also not critical illness cover or income protection, which pay on diagnosis of a specified condition or on inability to work. A whole of life policy pays on death, and on terminal illness only if the policy includes that benefit and the definition in the wording is met.</p>

<p>On guaranteed acceptance over-50s plans, two structural features matter before buying. There is normally a waiting period at the outset, during which death from natural causes does not produce the full sum assured. And because premiums usually continue for life or to a stated age, it is possible to pay in more than the plan pays out if you live long enough. The detail depends entirely on the individual plan terms.</p>

<h2>Common things that go wrong</h2>

<ul class="na-checklist">
<li>The policy was never placed in trust, so proceeds fall into the estate, may be delayed by probate, and may increase the very inheritance tax bill the cover was meant to fund.</li>
<li>Nobody knew the policy existed. Beneficiaries cannot claim on a plan they cannot find.</li>
<li>A reviewable plan reached its first review and the increase was unaffordable, so cover was reduced or lost.</li>
<li>An indexation option was declined years ago and the sum assured no longer bears any relation to the cost it was meant to meet.</li>
<li>Medical or lifestyle information was incomplete at application, and the insurer raises questions at claim. Insurers can and do review disclosure when a claim is made.</li>
<li>Premiums were paid from an account that was later closed, and the policy lapsed unnoticed.</li>
</ul>

<h2>Joint or single</h2>

<p>Joint life first death pays on the first of the two deaths and then ends. Joint life second death pays only when the second person dies, and is frequently used where the purpose is an inheritance tax liability arising on the second estate. Two single policies cost more in total but leave two separate claims and no shared fate if the relationship ends. Which structure fits depends on the purpose of the cover and on your circumstances.</p>

<h2>What an adviser will want to understand</h2>

<ul class="na-checklist">
<li>What the money is actually for: funeral costs, an expected inheritance tax charge, a dependant with lifelong needs, or a business arrangement.</li>
<li>Whether the need is genuinely permanent, or whether a long term assurance policy would meet it.</li>
<li>What you can sustain as a premium not just now, but through retirement, and whether premium certainty matters more to you than a lower starting cost.</li>
<li>Any existing policies, including old unit-linked or reviewable plans, and the current review position on each.</li>
<li>Your health and medical history in full, including anything you think is minor or historic.</li>
<li>Who should receive the money, and whether a trust is appropriate, together with who could act as trustee.</li>
<li>What other provision exists, such as death in service benefit or pension death benefits, that reduces or changes the gap.</li>
<li>Your wider estate position, including your will, so the policy and the will do not pull in different directions.</li>
</ul>

<p class="na-disclaimer">This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.</p>');
    }

    public static function guide_writing_life_insurance_in_trust(): string
    {
        return self::html('<nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a> <span aria-hidden="true">/</span> <a href="/guides/">Guides</a> <span aria-hidden="true">/</span> <span>Life cover in trust</span></nav>

<p class="na-eyebrow">Estate planning</p>

<p class="na-lede">Putting a life policy in trust decides who receives the money, how quickly it arrives, and whether it counts as part of your estate. This guide walks through what a trust does, the main types, and the steps involved.</p>

<h2>What a trust does to a life policy</h2>

<p>A trust separates legal ownership from benefit. You, the settlor, hand the policy to trustees, who hold it legally, for the benefit of the people you name, the beneficiaries. The insurer then pays the claim to the trustees rather than to your estate, and the trustees pass it on according to the trust.</p>

<p>Three practical effects follow, though each depends on the type of trust and on your circumstances.</p>

<ul>
<li>Speed. Trustees can usually claim without waiting for probate, so money can reach a family in weeks rather than months.</li>
<li>Estate position. The proceeds are generally outside your estate for inheritance tax, so they do not add to the value on which tax is calculated.</li>
<li>Direction. The money goes where the trust says, not where your will says, and not where the intestacy rules would send it if you had no will.</li>
</ul>

<p>That last point cuts both ways. A trust is a decision about destination, made in advance, and depending on the type chosen it can be difficult or impossible to reverse.</p>

<h2>The main types of trust used for protection policies</h2>

<div class="na-callout-grid">
<div class="na-callout"><h3>Absolute or bare trust</h3><p>Beneficiaries are fixed at the outset and cannot be changed later. Simple and certain, but inflexible if relationships or family circumstances change.</p></div>
<div class="na-callout"><h3>Discretionary or flexible trust</h3><p>Trustees choose from a class of potential beneficiaries, guided by a letter of wishes. Flexible if your circumstances change, but discretionary trusts have their own tax and reporting framework.</p></div>
<div class="na-callout"><h3>Split trust</h3><p>Used where a policy includes benefits you may need yourself, such as critical illness. Death benefit is held for the beneficiaries while the living benefits are retained by you.</p></div>
<div class="na-callout"><h3>Business trust</h3><p>Used for business protection such as shareholder or partnership cover, so the benefit reaches the right people under the terms of a linked agreement.</p></div>
</div>

<p>Insurers publish their own standard trust forms for use with their policies, which is the usual route and costs nothing. A solicitor can draft a bespoke trust where the circumstances need it.</p>

<h2>The steps</h2>

<ol>
<li>Decide whether a trust is right for this policy. It is not automatic. A joint life policy between spouses paying to the survivor, or a policy assigned to a lender, may not need one. A policy you might want to use yourself, or one intended to fund a specific liability, needs the type chosen with that purpose in mind.</li>
<li>Choose the type of trust. The main question is certainty against flexibility, and whether any living benefits need to stay with you.</li>
<li>Choose your trustees. You are normally a trustee yourself. Appoint at least one other, and preferably two, because a sole surviving trustee creates a problem when you die. Trustees should be adults you trust, capable of handling a claim and dealing with paperwork, and ideally not all of the same generation.</li>
<li>Identify the beneficiaries. Name them clearly with full names, dates of birth and their relationship to you. Under a discretionary trust you name a class of potential beneficiaries and write a letter of wishes explaining how you would like the trustees to exercise their discretion.</li>
<li>Complete the insurer\'s trust form. Do this at application where possible, since setting up a trust at outset avoids the question of whether transferring an existing policy is itself a transfer of value.</li>
<li>Sign and witness correctly. This is where most trusts fail. Every settlor and every trustee must sign, the form must be dated, and signatures must be witnessed as the form requires. A witness should be someone unconnected to the trust, and should not be a beneficiary or the spouse of one.</li>
<li>Send the form to the insurer and keep a copy. Ask for written confirmation that the trust has been recorded against the policy, and keep that confirmation with your other important papers.</li>
<li>Tell the trustees. A trust nobody knows about does not work. Trustees need to know the policy exists, which insurer holds it, and where the paperwork is kept.</li>
<li>Check the registration position. Some trusts must be registered with HMRC\'s Trust Registration Service. Many pure protection trusts holding no value are excluded while that remains the case, but the position can change once a policy pays out or if the arrangement acquires value. This should be confirmed at the time.</li>
<li>Review after life events. Marriage, divorce, children, a death among the trustees or beneficiaries, or a significant change in assets are all reasons to revisit the arrangement.</li>
</ol>

<h2>Choosing trustees well</h2>

<p>Trustees do real work at a difficult moment. They notify the insurer, complete the claim, receive the money, and distribute it correctly. They have legal duties and must act in the beneficiaries\' interests.</p>

<p>A few practical points. Appointing your spouse alone is common but leaves nobody independent if you die together. Appointing only people of your own age creates the same succession problem later. Where beneficiaries are children, someone will need to hold the money until they are old enough to receive it, and the trust should make clear at what age that happens. Professional trustees, usually a solicitor, can be appointed where the situation is complicated, and they will charge for the role.</p>

<h2>Where trusts go wrong</h2>

<ul>
<li>The form is completed but never sent to the insurer, so the policy is not actually in trust.</li>
<li>A trustee has not signed, the form is undated, or a beneficiary has witnessed a signature.</li>
<li>Only one trustee is appointed, and that trustee is the person who has died.</li>
<li>An absolute trust names a partner, the relationship ends, and the beneficiary cannot be changed.</li>
<li>A combined life and critical illness policy is placed in a full trust rather than a split trust, so a critical illness payment you needed for yourself is held for someone else.</li>
<li>The trust and the will point in different directions and were written years apart by different people.</li>
<li>Nobody told the trustees, so the family does not know the policy exists.</li>
<li>The policy is in trust but has been assigned to a lender, or vice versa, and the two arrangements conflict.</li>
</ul>

<h2>Trusts, tax and where other advice is needed</h2>

<p>Putting a policy in trust is a legal act with tax consequences. Placing an existing policy in trust can be a transfer of value, premiums paid on a policy held in trust are themselves transfers, and discretionary trusts sit within a regime that includes periodic and exit charges as well as reporting duties. For a term policy with no surrender value these charges frequently do not bite in practice, but that is a conclusion to reach on the facts rather than an assumption to start from.</p>

<p>Tax treatment depends on individual circumstances and current rules, both of which can change. A protection adviser can arrange cover and help you complete an insurer\'s standard trust form. Legal or tax advice may be needed alongside insurance advice, particularly where your estate is substantial, where a business is involved, where beneficiaries are minors or vulnerable, or where a bespoke trust is being considered. Nest Assured advises on protection insurance and does not provide legal or tax advice.</p>

<h2>What an adviser will want to understand</h2>

<ul class="na-checklist">
<li>Who you want the policy proceeds to reach, and whether that could change.</li>
<li>Your marital or civil partnership status, and whether any previous relationships create claims or obligations.</li>
<li>Whether any intended beneficiary is under eighteen, or would struggle to manage a large sum.</li>
<li>Who you would appoint as trustees, and whether they know and are willing.</li>
<li>Whether the policy includes critical illness or other benefits you might need yourself.</li>
<li>Whether the policy is, or will be, connected to a mortgage or assigned to a lender.</li>
<li>Whether the cover relates to a business, and whether a shareholder or partnership agreement exists.</li>
<li>Whether you have a will, when it was last reviewed, and whether it is consistent with the trust.</li>
<li>Existing policies and whether any of them are already in trust.</li>
<li>Whether a solicitor or accountant is already advising you, so the arrangements line up.</li>
</ul>

<p class="na-disclaimer">This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.</p>');
    }
}
