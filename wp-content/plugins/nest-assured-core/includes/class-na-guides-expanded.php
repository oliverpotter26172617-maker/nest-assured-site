<?php
/**
 * Guide library, first series, expanded.
 *
 * These thirteen guides originally ran to around three hundred words each, which
 * is not enough to be useful on a subject where the details decide whether a
 * claim is paid. They were rewritten to the same depth and the same rules as the
 * second series: no premium, payout, percentage or market statistic anywhere,
 * nothing that reads as a personal recommendation, and no claim about the firm.
 *
 * @package NestAssuredCore
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

final class NA_Guides_Expanded
{
    /**
     * @return array<int, array{0:string,1:string,2:string}>
     */
    public static function guides(): array
    {
        return [
            ['Buildings and contents insurance explained', 'buildings-and-contents-insurance', self::guide_buildings_and_contents_insurance()],
            ['Choosing private medical insurance', 'choosing-private-medical-insurance', self::guide_choosing_private_medical_insurance()],
            ['Income protection and employer sick pay', 'income-protection-and-sick-pay', self::guide_income_protection_and_sick_pay()],
            ['Income protection for self-employed people', 'income-protection-for-self-employed', self::guide_income_protection_for_self_employed()],
            ['Insurance jargon buster', 'insurance-jargon-buster', self::guide_insurance_jargon_buster()],
            ['Leaving a company private medical scheme', 'leaving-company-private-medical-insurance', self::guide_leaving_company_private_medical_insurance()],
            ['Life insurance and trusts', 'life-insurance-and-trusts', self::guide_life_insurance_and_trusts()],
            ['Life insurance or critical illness cover?', 'life-insurance-vs-critical-illness-cover', self::guide_life_insurance_vs_critical_illness_cover()],
            ['Making a protection insurance claim', 'making-a-protection-insurance-claim', self::guide_making_a_protection_insurance_claim()],
            ['Preparing for a protection appointment', 'preparing-for-protection-appointment', self::guide_preparing_for_protection_appointment()],
            ['Relevant life cover or key person cover?', 'relevant-life-vs-key-person-cover', self::guide_relevant_life_vs_key_person_cover()],
            ['Types of business protection explained', 'types-of-business-protection', self::guide_types_of_business_protection()],
            ['When should you review protection insurance?', 'when-to-review-protection-insurance', self::guide_when_to_review_protection_insurance()],
        ];
    }

    /**
     * @return array<string, array{title:string,description:string,eyebrow:string}>
     */
    public static function meta(): array
    {
        return [
            'buildings-and-contents-insurance' => ['title' => 'Buildings and contents insurance explained', 'description' => 'Rebuild cost versus market value, single item limits, accidental damage, escape of water, unoccupancy, underinsurance, leasehold cover and lender rules.', 'eyebrow' => 'Home insurance'],
            'choosing-private-medical-insurance' => ['title' => 'Choosing private medical insurance', 'description' => 'How moratorium and full medical underwriting differ, what acute and chronic mean, plus hospital lists, outpatient limits, excesses and the six week option.', 'eyebrow' => 'Private medical insurance'],
            'income-protection-and-sick-pay' => ['title' => 'Income protection and employer sick pay', 'description' => 'How contractual sick pay, Statutory Sick Pay, group schemes and state support fit together, and where an income protection deferred period sits.', 'eyebrow' => 'Protecting income'],
            'income-protection-for-self-employed' => ['title' => 'Income protection for self-employed people', 'description' => 'How self-employed income is evidenced by insurers, why sick pay rules do not apply, and how deferred periods, occupation definitions and costs work.', 'eyebrow' => 'Self-employed protection'],
            'insurance-jargon-buster' => ['title' => 'Insurance jargon buster', 'description' => 'A plain-English A to Z of UK protection insurance terms, covering underwriting, policy structure, claims and the wording that decides what a claim pays.', 'eyebrow' => 'Plain-English glossary'],
            'leaving-company-private-medical-insurance' => ['title' => 'Leaving a company private medical scheme', 'description' => 'What happens to employer medical cover when you leave, including continuation terms, underwriting you may lose, deadlines and treatment already under way.', 'eyebrow' => 'Private medical insurance'],
            'life-insurance-and-trusts' => ['title' => 'Life insurance and trusts', 'description' => 'How a trust decides who controls a life policy and who benefits, covering settlor, trustees, bare and discretionary forms, probate and estate consequences.', 'eyebrow' => 'Policy ownership'],
            'life-insurance-vs-critical-illness-cover' => ['title' => 'Life insurance or critical illness cover?', 'description' => 'How life insurance and critical illness cover differ, what triggers a claim under each, why definitions matter, and the structure questions worth asking.', 'eyebrow' => 'Personal protection'],
            'making-a-protection-insurance-claim' => ['title' => 'Making a protection insurance claim', 'description' => 'Who assesses a protection claim, the evidence insurers usually request, why claims take time, and what to do if a claim is declined or delayed.', 'eyebrow' => 'Claims and support'],
            'preparing-for-protection-appointment' => ['title' => 'Preparing for a protection appointment', 'description' => 'What happens in a protection appointment, the documents and health details to gather first, and the questions worth asking an adviser before you decide.', 'eyebrow' => 'Appointment checklist'],
            'relevant-life-vs-key-person-cover' => ['title' => 'Relevant life cover or key person cover?', 'description' => 'How employer-arranged relevant life cover differs from key person cover: who owns the policy, who gets the benefit, eligibility and tax points to check.', 'eyebrow' => 'Business protection'],
            'types-of-business-protection' => ['title' => 'Types of business protection explained', 'description' => 'Key person, shareholder, partnership and business loan protection, relevant life and executive income protection, and why ownership must match the purpose.', 'eyebrow' => 'Business protection'],
            'when-to-review-protection-insurance' => ['title' => 'When should you review protection insurance?', 'description' => 'The life, mortgage, work and health events that justify a protection review, why not to cancel before new cover is in force, and what a policy can change.', 'eyebrow' => 'Protection reviews'],
        ];
    }

    private static function html(string $content): string
    {
        return "<!-- wp:html -->\n" . trim($content) . "\n<!-- /wp:html -->";
    }

    public static function guide_buildings_and_contents_insurance(): string
    {
        return self::html('<section class="na-section"><div class="na-shell na-prose">
<nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a> <span aria-hidden="true">/</span> <a href="/guides/">Guides</a> <span aria-hidden="true">/</span> <span>Buildings and contents</span></nav>

<p class="na-eyebrow">Home insurance</p>

<p class="na-lede">Buildings insurance covers the structure. Contents insurance covers what is inside it. They are usually sold together, but they are separate covers with separate sums insured, separate excesses and separate ways of going wrong.</p>

<h2>Where the line between them falls</h2>

<p>The working test is whether you could reasonably take the item with you when you move. Structure, roof, walls, permanent fixtures, fitted kitchens and bathrooms and usually fitted flooring sit on the buildings side. Furniture, clothing, electronics and personal possessions sit on the contents side. The edges are less obvious: fitted wardrobes, garden structures, fencing, driveways, solar panels and carpets are treated differently by different insurers, sometimes under a sub section with its own limit.</p>

<h2>Rebuild cost is not market value</h2>

<p>The buildings sum insured is the cost of rebuilding the property, not the price it would sell for. The two are unrelated. A sale price includes the land and reflects the market, while the rebuild cost reflects labour, materials, demolition, site clearance, professional fees and compliance with current building regulations. Depending on where the property is, the rebuild cost can sit well below the market value or well above it, so using the purchase price as the sum insured goes wrong in both directions.</p>

<h3>Getting the figure from a sensible source</h3>

<p>For a conventional house of standard construction, rebuild calculators based on published building cost data are generally adequate, and a lender\'s valuation report will often quote a reinstatement figure. Some insurers instead rate by number of bedrooms and set their own sum insured, or offer an unlimited or declared value basis, which shifts the estimating risk to them provided your information was accurate. Listed buildings, thatch, timber frame, stone and flats above commercial premises usually need a professional reinstatement cost assessment, since listed status can require like for like materials and methods.</p>

<h2>Underinsurance, and how it reduces a settlement</h2>

<p>Underinsurance is the risk that the sum insured is lower than the true value of what you are insuring, and its effect is not limited to total losses. Many policies contain an average condition, allowing the insurer to reduce a settlement in proportion to the shortfall, so a partial claim can be scaled down and you meet the difference. Contents underinsurance is the more common failure, because most households underestimate the cost of replacing everything they own. Working room by room, including the loft, the garage and anything in storage, beats a guess at the front door.</p>

<p>A separate risk sits in the information you give. Under consumer insurance law you must take reasonable care not to make a misrepresentation when you apply or renew. The remedy available to the insurer depends on whether a misrepresentation was careless, or deliberate or reckless, and it can reduce a claim or, in serious cases, allow the policy to be avoided. Previous claims, subsidence history, flooding, unusual construction, business use and periods of letting all need declaring accurately.</p>

<h2>Single item limits and valuables</h2>

<p>Contents policies cover your possessions up to the overall sum insured, but they also cap the amount payable for any one item unless it has been specified. That single article limit is where most disappointment happens, because it applies whether or not the total sum insured is generous.</p>

<ul>
<li>An item above the single article limit generally needs listing on the schedule, often supported by a valuation or receipt.</li>
<li>There is usually a separate overall cap on total valuables, which can bite even where each item is individually within the limit.</li>
<li>Money, documents, contents in outbuildings and property in the open often carry their own smaller limits.</li>
<li>Items taken out of the home are usually only covered if personal possessions cover is added, and that has its own limits and territorial scope. Bicycles and mobile phones are frequently excluded from it.</li>
<li>Valuations date. A ring insured at its value some years ago may no longer sit within the limit on the schedule.</li>
</ul>

<h3>New for old and indemnity</h3>

<p>Most contents policies settle on a new for old basis, though clothing and linen are frequently settled on an indemnity basis with a deduction for wear and tear. Insurers usually reserve the right to repair or replace rather than pay cash, and where a matching set is damaged, many will not replace the undamaged parts.</p>

<h2>Accidental damage</h2>

<p>Standard cover responds to defined events such as fire, storm, flood, theft, impact and escape of water. It does not cover the ordinary mishaps of daily life. Accidental damage cover fills that gap: paint on the carpet, a foot through the loft ceiling, a drill through a pipe. It is usually optional and sold separately for buildings and for contents, so it is possible to hold one and not the other. Damage caused by pets, and damage occurring gradually rather than suddenly, are commonly excluded even so.</p>

<h2>Escape of water</h2>

<p>Escape of water, meaning water leaking from pipes, tanks or appliances, is one of the most frequent and most expensive causes of household claims. The damage caused by the water is the straightforward part. Finding the leak is the harder part. Trace and access cover pays the cost of locating the source and getting to it, including lifting floors or removing tiling, and putting that back afterwards. It normally carries its own limit, and the failed pipe or appliance itself is usually not covered, only the resulting damage.</p>

<p>Escape of water often carries a higher excess than other claims. Policies also commonly impose conditions in cold weather and during absences, such as keeping the heating on at a minimum temperature or draining the system, and a claim for freezing damage can be affected if those conditions were not met.</p>

<h2>Unoccupancy</h2>

<p>Almost every home policy limits cover once a property has been unoccupied for a continuous period, with the number of days set out in the wording. Beyond that point, cover typically falls back to a reduced set of perils such as fire, lightning and explosion, while theft, escape of water and malicious damage are commonly excluded. This catches people out in ordinary situations: an extended trip, a long hospital stay, working abroad, a renovation, or a property standing empty during probate. The obligation is normally on you to tell the insurer, which can allow an endorsement with conditions such as regular inspections.</p>

<h2>Leasehold flats and shared buildings</h2>

<p>If you own a leasehold flat, you usually do not arrange the buildings insurance yourself. The freeholder or management company insures the whole building under a block policy and recovers the cost through the service charge. Your responsibility is normally contents, and the lease determines the rest.</p>

<ul>
<li>Read the lease to establish who insures what, and where responsibility for internal fixtures, fittings and improvements sits.</li>
<li>Ask for the block policy schedule and check the sum insured, the perils and the excesses, particularly for escape of water, which may be recharged to the leaseholder responsible.</li>
<li>Improvements you have made may not be reflected in the block policy sum insured. Some contents policies can cover tenant\'s improvements.</li>
<li>Where you own a share of the freehold, the owners arrange the building cover collectively, so getting the sum insured right becomes your responsibility.</li>
</ul>

<h2>What a mortgage lender typically requires</h2>

<p>Lenders take security over the property, so they require buildings insurance in force from the point you become responsible for it, with a sum insured at least matching the reinstatement figure the lender has identified and cover against the standard perils. In England and Wales most residential contracts use the Standard Conditions of Sale, under which the seller keeps the risk until completion, but many conveyancers and lenders still want the buyer\'s buildings insurance in force from exchange, and some contracts are varied to pass risk at that point. In Scotland the position depends on the missives. Confirm with your conveyancer and your lender the exact date you must be insured from, rather than assuming it is moving day. For a leasehold flat the lender will usually want to see the block policy rather than a policy in your name. Contents insurance is not a lender requirement, it is a personal decision.</p>

<h2>Flood, subsidence and difficult risks</h2>

<p>Properties with a history of flooding or subsidence are not uninsurable but need more care. Flood Re is an industry and government backed reinsurance scheme designed to help make flood cover available for eligible homes, but eligibility rules apply, including that homes built on or after 1 January 2009 fall outside it. Where subsidence has occurred, the existing insurer is often better placed to continue cover than a new one, and previous claims, monitoring and completed underpinning all need disclosing.</p>

<h2>At renewal</h2>

<p>Insurers must not offer an existing customer a renewal price higher than the equivalent new business price for the same product through the same channel. What still changes is the cover. Check what has altered in the year: extensions and loft conversions, a new kitchen, new valuables, business equipment, a lodger, or periods when the home will be empty. A lower price with narrower cover is not a saving.</p>

<h2>What an adviser will want to understand</h2>

<ul class="na-checklist">
<li>The property type, age, construction, roof type and whether it is listed.</li>
<li>Whether it is freehold or leasehold, and what the lease or block policy already covers.</li>
<li>The rebuild figure and where it came from, rather than the purchase price.</li>
<li>A realistic room by room view of contents, including the loft, garage and storage.</li>
<li>Individual valuables that may exceed a single article limit, with current valuations.</li>
<li>Items regularly taken out of the home, and whether they travel abroad.</li>
<li>Any history of flooding, subsidence, escape of water or previous claims at the property.</li>
<li>Whether anyone works from home or keeps business stock or equipment there.</li>
<li>Whether the property will be empty for long periods, let, or undergoing building work.</li>
<li>Your lender\'s requirements and the date you become responsible for the property.</li>
</ul>

<p class="na-disclaimer">This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.</p>
</div></section>');
    }

    public static function guide_choosing_private_medical_insurance(): string
    {
        return self::html('<section class="na-section"><div class="na-shell na-prose">
<nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a> <span aria-hidden="true">/</span> <a href="/guides/">Guides</a> <span aria-hidden="true">/</span> <span>Choosing medical cover</span></nav>

<p class="na-eyebrow">Private medical insurance</p>

<p class="na-lede">Two private medical policies can look almost identical on a summary page and behave very differently the first time you need a consultant. The differences sit in the underwriting basis, the hospital list, the outpatient terms and the way the policy defines what it will treat.</p>

<h2>What private medical insurance is designed to do</h2>

<p>Private medical insurance is built around prompt access to diagnosis and treatment for conditions that can be resolved. It is not a parallel health service and does not replace the NHS. Emergency care, accident and emergency departments and the long-term management of ongoing conditions generally remain NHS territory, and most policies say so explicitly. What a policy really sells you is a route: how quickly you can see a consultant, which consultant, at which hospital, and how much of the diagnostic pathway is paid for before any treatment is agreed.</p>

<h2>Acute and chronic, the distinction everything rests on</h2>

<p>Almost every limit and exclusion traces back to one distinction. An acute condition responds to treatment and is expected to return you to the state of health you were in beforehand, or otherwise lead to full recovery. A chronic condition continues indefinitely, has no known cure, comes back or is likely to, needs ongoing monitoring or tests, or requires long-term control of symptoms. Insurers use similar wording for this, but not identical wording, and the definition in your policy is the one that applies.</p>

<h3>How chronic conditions are usually handled</h3>

<p>The common structure is that a policy covers the investigation and diagnosis of a new condition, and the initial treatment needed to bring it under control, then stops paying once the condition is judged chronic and in need of ongoing management. This surprises people, because a policy can pay for the tests that identify a condition and decline the treatment that follows. Cancer very often sits in its own section with its own rules rather than under the chronic exclusion.</p>

<h2>How your medical history will be assessed</h2>

<div class="na-callout-grid">
<div class="na-callout"><h3>Moratorium underwriting</h3><p>You give no medical detail at the outset. The policy automatically excludes conditions from a defined recent look-back period, and each may become eligible later if you go a continuous stated period without symptoms, treatment, medication or medical advice for it.</p></div>
<div class="na-callout"><h3>Full medical underwriting</h3><p>You declare your history when you apply. The insurer assesses it and sets out any exclusions in writing before cover starts, so you know where you stand from day one rather than at the point of claim.</p></div>
<div class="na-callout"><h3>Medical history disregarded</h3><p>Used on some employer arranged schemes. Pre-existing conditions are covered for members without individual assessment. It is a feature of group arrangements and is not normally available on personal policies.</p></div>
<div class="na-callout"><h3>Continued personal medical exclusions</h3><p>Used when moving between insurers, or from a scheme to a personal policy. The new insurer broadly applies the exclusions that already applied rather than underwriting you afresh. Whether it is offered depends on the insurer and the case.</p></div>
</div>

<h3>Moratorium in practice</h3>

<p>Moratorium is quicker to arrange and there is no form to complete. The trade-off is uncertainty: a condition you had years ago, or a symptom you mentioned to a GP and thought nothing of, may sit inside the look-back window without you realising, and you find out when the insurer reviews your records at claim. The clearing mechanism has conditions attached too. The clear period usually has to be continuous, and any consultation, medication, test or advice for that condition can restart it.</p>

<h3>Full medical underwriting in practice</h3>

<p>Full medical underwriting takes longer and requires you to be thorough, and its advantage is certainty. Exclusions are named on your certificate, so you know before you commit whether the thing you were most worried about is covered. They may be permanent, time limited, or reviewable after a period without symptoms or treatment. Accuracy matters: under consumer insurance law you must take reasonable care not to make a misrepresentation, and the insurer\'s remedies depend on whether an error was careless, or deliberate or reckless. If you are unsure of a date or a diagnosis, say so rather than guess.</p>

<h2>Hospital lists and consultant access</h2>

<p>Every insurer publishes hospital lists, and most sell several tiers of the same policy differentiated mainly by which list you get. A broad national list costs more. Restricted lists cost less and typically exclude the most expensive private hospitals, which often means certain central London facilities. A list is only useful if it works geographically and clinically, so check not only that a convenient hospital appears but that it provides the specialisms you might realistically need. Not every private hospital offers every service, and complex or high dependency care is not available everywhere.</p>

<h3>Fee approved consultants and shortfalls</h3>

<p>Insurers maintain lists of recognised consultants who have agreed to charge within the insurer\'s fee schedule. See a consultant who charges above that schedule, or one not recognised at all, and the difference is normally yours to pay. That gap is called a shortfall and it is a common source of unexpected bills. The protection is to confirm before the appointment that the consultant is recognised for the treatment concerned and will charge within the schedule, and to obtain authorisation first. Most policies require pre-authorisation anyway, and treatment taken without it may not be paid.</p>

<h2>Guided referral and open referral</h2>

<p>When your GP refers you, the policy decides how much choice you have. Under an open referral basis you can generally choose from the recognised list. Under a guided referral basis the insurer offers a shortlist of consultants or facilities and you choose from those. Guided arrangements usually cost less because the insurer is directing volume and controlling cost. What you give up is breadth of choice, and sometimes the ability to see a specific consultant you or your GP had in mind.</p>

<h2>Outpatient cover, where policies differ most</h2>

<p>Inpatient and day-patient cover is broadly comparable between mainstream policies. Outpatient cover is not, and it is where a cheaper premium usually comes from. Common structures include full outpatient cover, an annual monetary cap on outpatient benefits, a cap on consultations while diagnostics are paid in full, and cover restricted to diagnostic tests only with no consultations at all.</p>

<p>Because diagnosis usually happens in outpatients, a thin outpatient benefit can be exhausted before the condition has been identified. Therapies such as physiotherapy, osteopathy and chiropractic are often treated separately again, and mental health benefits frequently sit in a distinct section with their own limits and pathways.</p>

<h2>Excesses and the six week option</h2>

<p>An excess is the amount you contribute towards eligible claims, and raising it reduces the premium. The mechanics matter more than the amount. An excess may apply per policy year or per condition, and per person or per policy, and a per condition excess in a year with several unrelated problems behaves very differently from a single annual one.</p>

<p>The six week option works differently. If the NHS can provide the eligible treatment within six weeks of the date it is agreed you need it, you use the NHS and the policy does not pay for that treatment. If the wait would be longer, the policy pays. It reduces the premium, and it usually applies to admitted treatment rather than the outpatient diagnostic stage, though the wording sets the exact scope.</p>

<h2>What is normally excluded</h2>

<ul>
<li>Pre-existing conditions, in the way described above and subject to the underwriting basis chosen.</li>
<li>Ongoing management of chronic conditions once they are stable.</li>
<li>Accident and emergency attendance and emergency admission.</li>
<li>Normal pregnancy and childbirth, though complications are sometimes covered.</li>
<li>Cosmetic treatment, and dental and optical care unless a specific benefit is added.</li>
<li>Drugs and treatments the insurer regards as unproven, unlicensed or experimental.</li>
<li>Treatment outside the territorial limits of the policy.</li>
</ul>

<h2>Renewal, and why the price moves</h2>

<p>Private medical insurance is generally an annually renewable contract. Premiums are not fixed for life and typically reflect your age, medical inflation, the insurer\'s claims experience and, on some products, your own claims. Renewal terms can change benefits as well as price, so a renewal invitation deserves the same reading as a new quotation. Switching insurer is possible, but the crucial question is whether your underwriting position travels with you. Fresh underwriting can bring new exclusions for anything that has happened since you first took cover, and treatment already under way needs specific attention before anything is cancelled.</p>

<h2>What an adviser will want to understand</h2>

<ul class="na-checklist">
<li>Who needs covering, their ages, and whether they go on one policy or are insured separately.</li>
<li>Your medical history, and whether certainty about exclusions matters more than a faster application.</li>
<li>Any condition currently under investigation or being treated.</li>
<li>Which hospitals are realistically convenient, and whether a named consultant matters to you.</li>
<li>How much of the outpatient pathway you want covered, and whether therapies or mental health support are a priority.</li>
<li>Whether you already hold cover, when it renews, and on what underwriting basis.</li>
<li>Whether an employer scheme covers you now, and what happens to it if you change jobs.</li>
<li>How you would feel about a guided referral shortlist rather than an open choice.</li>
<li>What you can sustain as an ongoing cost, given that renewal premiums rise over time.</li>
</ul>

<p class="na-disclaimer">This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.</p>
</div></section>');
    }

    public static function guide_income_protection_and_sick_pay(): string
    {
        return self::html('<section class="na-section"><div class="na-shell na-prose">
<nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a> <span aria-hidden="true">/</span> <a href="/guides/">Guides</a> <span aria-hidden="true">/</span> <span>Employer sick pay</span></nav>
<p class="na-eyebrow">Protecting income</p>
<p class="na-lede">Income protection is normally arranged to start where your other support runs out. That means the first job is not choosing a policy, it is mapping honestly what an employer, the state and your savings would actually do, and for how long.</p>

<h2>Understand your employer\'s sick pay before anything else</h2>
<p>Contractual sick pay, sometimes called occupational or company sick pay, is whatever your employer has agreed to provide. It is set out in your contract, your staff handbook or a separate absence policy, and it varies enormously between employers. Some provide a period of full pay followed by a period at a reduced rate. Some provide nothing beyond the statutory minimum. Many make the entitlement depend on length of service, so what a long-serving colleague received tells you nothing about your own position.</p>
<p>Two details are commonly missed. First, entitlement is usually measured over a rolling period rather than reset by each new absence, so an earlier period off work can reduce what is available later. Second, discretionary schemes exist, where the employer decides case by case, and a discretionary benefit is not something you can plan a household around.</p>

<h2>How Statutory Sick Pay actually fits</h2>
<p>Statutory Sick Pay is the legal minimum an eligible employee receives from their employer during sickness absence. It is a common mistake to think SSP starts when contractual sick pay stops. It does not. SSP runs alongside contractual sick pay, and where an employer pays more than the statutory minimum, the SSP is normally included within that payment rather than paid on top of it. Only where there is no contractual scheme, or where contractual pay has fallen below the statutory amount, does SSP become the visible part of what you receive.</p>
<p>SSP is paid by the employer, is subject to eligibility conditions, and can run only for a limited maximum period set in legislation. The rules, including the qualifying conditions and the rate, are set by government and have been subject to reform, so check the current position on GOV.UK rather than relying on what was true a few years ago. Where an employee is not eligible, or SSP entitlement ends while they are still unable to work, the employer issues form SSP1, which supports a claim for state benefits instead.</p>

<h2>What the state does next, and what it does not do</h2>
<p>Once employer support has ended, the usual routes are New Style Employment and Support Allowance, which depends on your National Insurance record rather than your savings, and Universal Credit, which is means tested and takes account of savings, capital and a partner\'s income. Both involve an assessment of your capability for work, and neither is designed to maintain the standard of living most working households are used to.</p>
<p>This matters in both directions. State support is unlikely to fill the gap on its own, but income protection benefit can also affect entitlement to means tested support, because it counts as income. If you expect to rely on Universal Credit alongside a policy, that interaction is worth raising specifically, because your circumstances determine the outcome.</p>

<h2>Group income protection, if you have it</h2>
<p>Some employers provide group income protection, which pays a benefit to the employer, who then normally passes it to the employee through payroll with tax and National Insurance deducted as earnings. Where this exists, it changes the whole picture, and you need the scheme booklet rather than a summary line on a benefits portal. The points to establish are when the benefit starts, how long it can continue, whether it stops if you leave employment or are dismissed, whether it ends at a set age, and how it is calculated against your earnings. Group cover is an employer benefit, not a personal policy, so it can be changed or withdrawn by the employer.</p>

<h2>Building the timeline</h2>
<ol>
  <li>Write down the exact periods of full and reduced contractual sick pay you would receive, and whether service or discretion affects them.</li>
  <li>Add any group income protection, including when it would begin and how long it could run.</li>
  <li>Work out how many months accessible savings could cover essential spending without disrupting other plans.</li>
  <li>Identify the point at which the household income actually falls below what the essentials cost. That point, not an arbitrary preference, is what a deferred period should be matched against.</li>
</ol>
<p>A longer deferred period generally reduces the premium, because the insurer is covering fewer claims and shorter ones. That is a genuine trade-off rather than a free saving, and it only works if the earlier part of the timeline is real rather than optimistic.</p>

<h2>The features that shape an individual policy</h2>
<div class="na-callout-grid">
  <div class="na-callout"><h3>Incapacity definition</h3><p>Own occupation tests whether you can do your own job. Suited occupation tests whether you could do another job matching your skills or experience. Activities of daily work tests functional tasks rather than a job at all. These are not equivalent.</p></div>
  <div class="na-callout"><h3>Deferred period</h3><p>The waiting period before benefit can begin. Some policies allow claims to be linked, so a recurrence within a defined window does not restart the wait in full.</p></div>
  <div class="na-callout"><h3>Claim length</h3><p>Short term policies pay for a maximum period per claim. Full term policies can potentially pay until the policy end date, provided the claim remains valid.</p></div>
  <div class="na-callout"><h3>Proportionate benefit</h3><p>Many policies can continue a reduced benefit if you return to work in a lesser capacity or on lower earnings, subject to the policy terms.</p></div>
</div>

<h2>Do not insure the same money twice</h2>
<p>Insurers cap the benefit they will pay by reference to your earnings, and they take account of other continuing income, which can include group scheme benefits and certain state benefits. Over-insuring does not produce a bigger payout, it produces a premium for cover that cannot be paid at claim stage. If your circumstances change, particularly if you gain or lose an employer scheme or your earnings change materially, tell the insurer and review the cover rather than assuming the policy adjusts itself.</p>
<p>It also works the other way. Benefit is normally calculated on earnings evidenced at claim, not on the figure quoted at application, so someone whose income has fallen may find the payable benefit lower than expected. Ask how your insurer defines earnings and at what point it verifies them.</p>

<h2>Points that are easy to get wrong</h2>
<ul>
  <li>Income protection responds to illness or injury preventing work. It is not redundancy or unemployment cover, which is a different product with different terms.</li>
  <li>A death in service benefit is a lump sum paid on death. It provides nothing at all while you are alive and unable to work.</li>
  <li>Critical illness cover pays on a listed diagnosis. Many of the causes of long term absence, including musculoskeletal problems and mental health conditions, do not appear on those lists but are commonly assessed under income protection.</li>
  <li>Premiums may be guaranteed, reviewable or age related, and each behaves differently over a long term.</li>
  <li>Benefit from a personal policy paid for from taxed personal income is generally received without further income tax, but tax treatment depends on individual circumstances and current rules, both of which can change, and specialist advice may be needed.</li>
</ul>

<h2>What an adviser will want to understand</h2>
<ul class="na-checklist">
  <li>Your contractual sick pay terms in writing, including whether service or discretion applies</li>
  <li>Details of any group income protection or other employer scheme, from the scheme documents</li>
  <li>Your gross and net earnings, and how they are made up</li>
  <li>Essential monthly household spending, separated from discretionary spending</li>
  <li>Accessible savings and how long you would be willing to run them down</li>
  <li>Your occupation, duties and working pattern, including any second job or contract work</li>
  <li>Full medical history, including anything currently under investigation</li>
  <li>Whether anyone else in the household earns, and whether means tested support is part of your position</li>
</ul>

<p class="na-disclaimer">This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.</p>
</div></section>');
    }

    public static function guide_income_protection_for_self_employed(): string
    {
        return self::html('<section class="na-section"><div class="na-shell na-prose">
<nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a> <span aria-hidden="true">/</span> <a href="/guides/">Guides</a> <span aria-hidden="true">/</span> <span>Self-employed cover</span></nav>
<p class="na-eyebrow">Self-employed protection</p>
<p class="na-lede">Without an employer scheme, the gap between stopping work and receiving money is yours to bridge. Understanding how long that gap is, and how an insurer would measure your income, matters more than comparing headline premiums.</p>

<h2>Start with what actually stops</h2>
<p>An employee who falls ill has a sequence of support to work through. If you are self-employed, most of that sequence does not exist. There is no contractual sick pay. If you are a sole trader or a partner, Statutory Sick Pay does not apply to you at all, because it is a payment made by an employer to an employee. If you run your own limited company and take a salary through PAYE you are technically an employee of that company and may qualify, but the company funds it, and the company usually depends on you being at work. The realistic sources of money are your savings, whatever the business can keep generating without you, help from a partner or family, state benefits, and any insurance you have arranged in advance.</p>
<p>State support usually means New Style Employment and Support Allowance, which depends on your National Insurance record, or Universal Credit, which is means tested and takes account of savings, capital and a partner\'s income. National Insurance rules for the self-employed have changed in recent years, including how Class 2 contributions work, so if entitlement to contribution based support matters to you, check your record and the current rules on GOV.UK rather than assuming.</p>

<h2>Separate the household gap from the business gap</h2>
<p>These are two different problems and they are often merged into one number, which produces cover that does not fit either.</p>
<p>The household gap is what your home needs each month regardless of whether you are working. Mortgage or rent, utilities, food, childcare, insurance, transport, loan payments. This is what a personal income protection policy is normally arranged around.</p>
<p>The business gap is the cost of keeping the business alive while you cannot run it. Premises, equipment leases, professional subscriptions, insurance, accountancy, staff, software. Some of these continue whether or not any work is being done. Business expense cover, sometimes called fixed overheads cover, is a separate type of policy built for exactly this, and it typically reimburses eligible costs actually incurred rather than paying a fixed sum. Whether that is relevant depends entirely on whether you carry real fixed costs.</p>

<h2>How insurers work out what you earn</h2>
<p>This is the part that catches self-employed applicants out, because the definition of earnings is set by the insurer, not by you or your accountant. Broadly:</p>
<ul>
  <li>For a sole trader or partner, insurers usually work from net profit, or your share of it, before tax rather than from turnover or drawings.</li>
  <li>For a company director, the picture usually combines salary and dividends drawn from the profits of the business. Some insurers can also take account of a share of retained profit, which can be significant if you deliberately leave money in the company, but this is not universal.</li>
  <li>If you have recently started trading, insurers have their own rules on how short a trading history they will accept and how they average it.</li>
  <li>Where income fluctuates, the insurer may average it across a period, which can help or hurt depending on the direction of travel.</li>
</ul>
<p>Evidence commonly requested includes accounts, self assessment tax calculations, tax year overviews, business bank statements or written confirmation from an accountant. The key point is that earnings are normally verified at the point of claim as well as at application, so the sum insured needs to reflect what you can evidence then, not the best year you have ever had.</p>

<h2>Occupation definitions matter more when the job is physical or specialised</h2>
<p>A self-employed tradesperson, a dentist, a hairdresser and a consultant all face very different versions of the same question: what counts as being unable to work. An own occupation definition tests whether you can carry out your own job. A suited occupation definition tests whether you could do something else your skills or experience fit, which is a harder test to satisfy. An activities of daily work definition ignores your job entirely and looks at functional tasks.</p>
<p>Insurers also group occupations into classes, and the class assigned affects the terms offered, the definitions available and sometimes whether cover can be offered at all. Describe your actual duties, not just your job title. A self-employed builder who now mostly project manages, and a director who still climbs scaffolding at weekends, are both likely to be assessed on what they really do.</p>

<h2>Choosing a deferred period when there is no sick pay</h2>
<p>Employees often set the deferred period to the point their employer support falls away. You have no such marker, so the honest question is how many months of essential household and business costs you could genuinely cover from accessible savings without wrecking anything else. A longer deferred period usually reduces the premium, because fewer and shorter claims are covered, and a shorter one costs more but shortens the period you are self-funding.</p>
<p>Two related points are worth asking about. Some policies define the deferred period from the date you stop work, others from the date you first sought medical attention. And where a policy allows linked claims, a recurrence of the same condition within a defined window may not require you to serve the full wait again, which matters for conditions that come and go.</p>

<div class="na-callout-grid">
  <div class="na-callout"><h3>Partial return to work</h3><p>Many policies can pay a reduced benefit if you go back on lighter duties or fewer hours, which suits self-employed people who rarely stop and restart cleanly.</p></div>
  <div class="na-callout"><h3>Rehabilitation support</h3><p>Some insurers provide practical support alongside the money, such as help managing a return to work. The value of this depends on the insurer and the condition.</p></div>
  <div class="na-callout"><h3>Increasing cover</h3><p>Guaranteed insurability options can allow cover to be increased on defined events without full medical underwriting, subject to the policy terms.</p></div>
  <div class="na-callout"><h3>Premium basis</h3><p>Guaranteed, reviewable or age related premiums behave very differently across a long term, and an unaffordable premium later is a real risk.</p></div>
</div>

<h2>Sole trader, partnership or limited company</h2>
<p>Your structure changes which arrangements can even be considered. A sole trader is generally looking at a personal policy paid from personal funds. A partnership may need to think about what happens to the other partners as well as the household. A limited company opens the possibility of company-paid arrangements, including executive income protection, where the company owns the policy and benefit is normally paid to the company and then through payroll.</p>
<p>Company-paid arrangements bring in corporation tax, income tax, National Insurance and benefit in kind questions that depend on your individual circumstances and on current rules, both of which can change. Your accountant, and in some cases a tax specialist, should be involved before anything is set up on that basis.</p>

<h2>Practical points that get overlooked</h2>
<ul>
  <li>Income protection covers illness and injury preventing work. It does not cover a downturn, a lost contract or a client going under.</li>
  <li>If your income changes materially, the cover should be reviewed. Insurers cap benefit against earnings, so cover you have outgrown, or over-insured, both cause problems.</li>
  <li>Benefit can affect entitlement to means tested support, because it counts as income.</li>
  <li>Tax treatment of premiums and benefit depends on how the policy is owned and paid for, on your individual circumstances and on current rules, all of which can change.</li>
</ul>

<h2>What an adviser will want to understand</h2>
<ul class="na-checklist">
  <li>Your trading structure, how long you have traded, and who else depends on the business</li>
  <li>What your duties actually involve day to day, including any physical or hazardous elements</li>
  <li>Recent accounts, tax calculations and tax year overviews, and how you draw money</li>
  <li>Essential household costs, separated from business costs that would continue</li>
  <li>Accessible savings and how many months they would realistically cover</li>
  <li>Any existing personal or business cover, and whether the business owns any of it</li>
  <li>Full medical history, including anything under investigation or previously excluded by an insurer</li>
  <li>Whether an accountant or tax adviser is already involved and should be part of the conversation</li>
</ul>

<p class="na-disclaimer">This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.</p>
</div></section>');
    }

    public static function guide_insurance_jargon_buster(): string
    {
        return self::html('<section class="na-section"><div class="na-shell na-prose">
<nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a> <span aria-hidden="true">/</span> <a href="/guides/">Guides</a> <span aria-hidden="true">/</span> <span>Jargon buster</span></nav>

<p class="na-eyebrow">Plain-English glossary</p>

<p class="na-lede">Protection insurance has a vocabulary of its own, and most of it exists for good reason. These plain-English definitions explain the terms you are most likely to meet, from application through to claim. The policy wording always remains the final reference, and definitions can differ between insurers.</p>

<h2>How to use this glossary</h2>

<p>Two words of caution before the list. First, many of these terms have a general meaning and a policy-specific meaning, and only the second one decides whether a claim pays. When an insurer uses a defined term, it will normally appear in the policy wording with a precise definition attached to it. Second, wording varies between insurers and between generations of the same product, so an older policy may use a term differently from a new one. If a definition here does not match your documents, your documents win.</p>

<h2>A to C</h2>

<h3>Assignment</h3>
<p>The legal transfer of a policy, or of rights under it, from one party to another. A lender may ask for a policy to be assigned to it so that the proceeds are used to repay borrowing before anything else. Assignment is a formal step with legal consequences, not a change of address.</p>

<h3>Beneficiary</h3>
<p>A person or organisation intended to receive money from a policy, usually through a trust. Where a policy is held on trust, the beneficiaries are defined by the trust deed rather than by the insurer.</p>

<h3>Benefit period</h3>
<p>On income protection, the maximum length of time benefit can be paid for a single claim. Some plans pay for as long as incapacity continues up to the end of the policy, others limit each claim to a shorter period. This choice significantly changes what a policy is worth in a long claim.</p>

<h3>Cancellation rights</h3>
<p>A period after a policy starts during which it can be cancelled with a refund of premiums paid, sometimes called a cooling-off period. The length and conditions are set out in the policy documents.</p>

<h3>Continuation option</h3>
<p>A facility offered by some policies allowing cover to be continued in defined circumstances, such as when an employee leaves a scheme or an arrangement ends. Availability, timescales and any further underwriting depend entirely on the provider and the policy terms.</p>

<h3>Critical illness cover</h3>
<p>Cover that pays a lump sum if the person insured is diagnosed with one of the conditions listed in the policy and meets the definition attached to it, and survives any required period. The list and the definitions are what matter, not the name of the illness in everyday use.</p>

<h3>Cross option agreement</h3>
<p>A legal agreement used alongside shareholder or partnership protection. It gives the surviving owners the option to require a sale and the deceased owner\'s estate the option to require a purchase, which makes the transaction effectively certain without binding either side to sell from the outset. It is drafted by a solicitor, not by an insurer.</p>

<h2>D to F</h2>

<h3>Decreasing term assurance</h3>
<p>Life cover where the sum assured reduces over the term, often considered alongside a repayment mortgage. The rate at which cover reduces is set by the policy and will not track a specific loan balance exactly.</p>

<h3>Deferred period</h3>
<p>On income protection, the period of continuous incapacity that must pass before benefit starts to accrue. A longer deferred period usually means a lower premium, so it should be matched to sick pay, savings and other resources rather than chosen for price alone.</p>

<h3>Disclosure</h3>
<p>The duty to answer the insurer\'s questions fully, honestly and to the best of your knowledge when applying, and to tell the insurer about anything it asks you to update before the policy starts. Careless or deliberate failures can affect a claim.</p>

<h3>Excess</h3>
<p>An amount the policyholder contributes towards an eligible claim, used mainly in private medical insurance and general insurance rather than in life or critical illness cover.</p>

<h3>Exclusion</h3>
<p>A circumstance, condition or event the policy does not cover. Exclusions may be standard for the product or applied to an individual policy following underwriting, for example excluding claims relating to a particular part of the body.</p>

<h3>Financial Ombudsman Service</h3>
<p>The independent service that considers complaints about financial firms where the firm\'s own complaints process has not resolved matters. Eligibility criteria and time limits apply, and its decisions are made case by case.</p>

<h3>Financial Services Compensation Scheme</h3>
<p>The statutory scheme that may provide protection if an authorised firm cannot meet its liabilities. The cover available, and the limits that apply, depend on the type of product and the scheme\'s own rules.</p>

<h3>Financial underwriting</h3>
<p>The insurer\'s assessment of whether the amount of cover applied for is justified by the circumstances, separate from any assessment of health. Evidence of income, borrowing or business value may be requested, particularly for larger sums.</p>

<h3>Full medical underwriting</h3>
<p>An approach where health and lifestyle information is assessed before cover starts, so that the terms of the policy are known at outset rather than being investigated at claim.</p>

<h2>G to L</h2>

<h3>Guaranteed insurability option</h3>
<p>A feature on some policies allowing cover to be increased on specified life events, such as a house move or the birth of a child, without full further medical underwriting. The qualifying events, notice periods and limits are defined in the policy.</p>

<h3>Guaranteed premiums</h3>
<p>A premium basis where the insurer cannot change the price during the term because of claims experience or a general review. Cover linked to indexation can still change, because the amount of cover is changing.</p>

<h3>Income protection</h3>
<p>Cover designed to pay a regular benefit if illness or injury prevents the insured person from working, after a deferred period and subject to the policy\'s definition of incapacity. It is not the same as short-term accident, sickness and unemployment cover.</p>

<h3>Indexation</h3>
<p>An arrangement under which the cover, and usually the premium, increase over time in line with a measure set out in the policy. It is intended to reduce the erosion of cover by inflation. Increases can normally be declined, though repeatedly declining may affect the option to increase later.</p>

<h3>Insurable interest</h3>
<p>The requirement that the person or business taking out cover would suffer a genuine financial loss if the insured event happened. It is the reason a business can insure a key employee and a stranger cannot.</p>

<h3>Joint life policy</h3>
<p>A single policy covering two people. A joint life first death policy pays out once, on the first claim, and then ends. A joint life second death policy pays on the later death. Two single policies behave differently from one joint policy, particularly if circumstances change.</p>

<h3>Key person cover</h3>
<p>Cover taken out and owned by a business on the life, and sometimes the health, of an individual it depends on. The business receives the benefit and uses it to absorb the financial disruption of losing that person.</p>

<h3>Level term assurance</h3>
<p>Life cover where the sum assured stays the same throughout the term. If no valid claim is made during the term, the policy ends with no value.</p>

<h3>Life assured</h3>
<p>The person whose death or illness the policy covers. This is not necessarily the person who owns the policy or pays for it, which is why ownership needs to be looked at separately.</p>

<h3>Loading</h3>
<p>An increase to the standard premium applied following underwriting, reflecting the insurer\'s assessment of the risk. An insurer may offer terms with a loading, with an exclusion, with both, or may postpone or decline an application.</p>

<h2>M to R</h2>

<h3>Medical evidence</h3>
<p>Information an insurer may request to assess an application or a claim, which can include a report from your GP, a targeted questionnaire, a nurse screening or a specialist\'s report. Your consent is required, and you can usually ask to see a GP report before it is sent.</p>

<h3>Moratorium underwriting</h3>
<p>An approach used mainly in private medical insurance where medical history is not assessed at the outset, but conditions from a defined period before the policy started are excluded, sometimes with the possibility of cover later if you remain free of them for a set time. The rules are specific to the insurer.</p>

<h3>Non-disclosure</h3>
<p>A failure to give the insurer the information it asked for when applying. The consequences depend on whether the failure was careless, or deliberate or reckless and on what the insurer would have done had it known, and can range from an adjustment to the claim to the policy being treated as though it never existed.</p>

<h3>Own occupation</h3>
<p>An income protection definition of incapacity based on your inability to carry out your own job. Other definitions, such as suited occupation or activities of daily work, are less generous and make a claim harder to establish. This single definition can matter more than the premium.</p>

<h3>Policy schedule</h3>
<p>The personalised document setting out who is covered, for how much, for how long, on what premium basis and with any individual exclusions. Read alongside the policy wording, which contains the general terms and definitions, it forms the contract. Missing premiums can cause cover to lapse, so the wording also sets out any grace period and reinstatement process.</p>

<h3>Pre-existing condition</h3>
<p>A condition that existed, or showed symptoms, before the policy started. How it is treated depends on the product and on the underwriting approach used.</p>

<h3>Proportionate benefit</h3>
<p>An income protection feature under which a reduced benefit may be paid if you return to work in a lower paid role, or work reduced hours, because of the condition that caused the claim. The calculation basis is defined in the policy.</p>

<h3>Relevant life policy</h3>
<p>An employer-arranged death-in-service policy covering a single employee or director, written under a suitable trust so that the benefit is held for the individual\'s family or dependants rather than for the business. It must meet conditions set out in the tax rules.</p>

<h3>Reviewable premiums</h3>
<p>A premium basis where the insurer can reassess the price at set points during the term. Premiums may rise at a review, sometimes significantly, so the initial price is not a reliable guide to the long-term cost.</p>

<h2>S to W</h2>

<h3>Settlor</h3>
<p>The person who creates a trust and puts a policy into it, usually the person covered. Once the trust is made, the settlor no longer owns the policy.</p>

<h3>Sum assured</h3>
<p>The amount of cover payable on a valid claim, as shown on the policy schedule. On decreasing or indexed policies it changes over the term.</p>

<h3>Survival period</h3>
<p>On critical illness cover, a period the insured person must survive after meeting the policy definition for a claim to be payable. The length is set by the insurer and appears in the wording.</p>

<h3>Terminal illness benefit</h3>
<p>A feature on many life policies allowing the sum assured to be paid early where a terminal diagnosis meets the policy definition, typically involving a prognosis within a defined period. The definition and any restrictions near the end of the term sit in the wording.</p>

<h3>Total permanent disability</h3>
<p>A benefit included in some critical illness policies covering permanent disability assessed against a defined test, which may be based on your own occupation, on any occupation, or on the ability to perform specified activities. The chosen test makes a large difference to what qualifies.</p>

<h3>Trust</h3>
<p>A legal arrangement separating ownership of a policy from the people intended to benefit from it. It can help direct proceeds to the intended people and allow trustees to deal with a claim without waiting for the estate to be administered. Tax treatment depends on individual circumstances and current rules, both of which can change.</p>

<h3>Trustee</h3>
<p>A person who legally holds and administers a policy held on trust, deals with the insurer and applies the proceeds under the trust deed. Trustees owe duties to the beneficiaries and may have record-keeping and registration obligations.</p>

<h3>Underwriting</h3>
<p>The insurer\'s process of assessing an application and deciding on what terms, if any, it will offer cover. It can involve medical, lifestyle, occupational, travel and financial questions, and may lead to standard terms, a loading, an exclusion, a postponement or a decline.</p>

<h3>Waiver of premium</h3>
<p>An option on some policies under which the premiums are waived while you are unable to work because of illness or injury, after a waiting period and subject to the policy definition. It keeps cover in force at the point it is most likely to be needed.</p>

<h3>Whole of life cover</h3>
<p>Life cover intended to remain in force for life rather than for a fixed term, provided premiums continue to be paid. Structures vary considerably, including whether premiums are guaranteed or reviewable, so the basis of the plan should be understood before comparing it with term cover.</p>

<h2>Where a glossary stops and advice begins</h2>

<p>Understanding the vocabulary makes it easier to follow a conversation, but the terms only combine into something useful once they are applied to your circumstances. The same word can produce a very different outcome depending on which insurer\'s wording it sits in, which is why comparing policies on headline features alone is unreliable. Nothing here is a personal recommendation, and tax or legal points raised by a trust or a business arrangement may need separate professional advice.</p>

<h2>Taking it to an adviser conversation</h2>

<ul class="na-checklist">
<li>Bring any existing policy schedules and wordings, or at least the insurer name and policy number</li>
<li>Note which terms in your documents you do not recognise, and ask what they mean in that specific policy</li>
<li>Ask which definitions decide whether a claim would pay, and how they differ between insurers</li>
<li>Ask whether premiums are guaranteed or reviewable, and what that means over the full term</li>
<li>Ask who would own each policy and who would receive the money</li>
<li>Ask what would need to be reviewed if your job, health, family or borrowing changed</li>
</ul>

<p class="na-disclaimer">This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.</p>
</div></section>');
    }

    public static function guide_leaving_company_private_medical_insurance(): string
    {
        return self::html('<section class="na-section"><div class="na-shell na-prose">
<nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a> <span aria-hidden="true">/</span> <a href="/guides/">Guides</a> <span aria-hidden="true">/</span> <span>Leaving a company scheme</span></nav>

<p class="na-eyebrow">Private medical insurance</p>

<p class="na-lede">Employer arranged private medical cover often includes terms that are hard to buy as an individual. When you leave, those terms do not automatically follow you, and the window to preserve any of them is usually short and tied to your leaving date.</p>

<h2>Why group cover is not the same thing as a personal policy</h2>

<p>A company private medical scheme is a contract between your employer and the insurer. You are a covered member, not the policyholder. That distinction is easy to overlook while the cover is running, and it explains almost everything that happens when you leave.</p>

<p>Group schemes are also frequently written on a medical history disregarded basis, meaning members are covered without individual underwriting and pre-existing conditions are not automatically excluded. That is genuinely valuable and it is not something you can simply go out and buy as an individual. Not every scheme works this way, and some apply moratorium terms or individual underwriting instead, so the scheme documents are worth reading rather than assuming.</p>

<h2>Cover ends when your membership ends</h2>

<p>Membership normally ends on your last day of employment, or on the date the scheme rules specify, which can differ if you are working a notice period, on garden leave or leaving part way through a scheme year. From that date you are no longer covered, regardless of when your treatment started.</p>

<p>The scheme administrator, usually someone in HR or an employee benefits team, can confirm the exact end date, the insurer, the scheme reference and the underwriting basis. You will need all four to arrange anything else, and they are far easier to obtain before you leave than afterwards.</p>

<h2>Treatment that is already under way</h2>

<p>This is the part that causes the most distress. Some group schemes contain a provision allowing an episode of treatment already authorised before the leaving date to continue for a limited period afterwards, sometimes called continued or run off treatment cover. Many schemes contain nothing of the sort, and cover simply stops.</p>

<p>You cannot assume such a provision exists. If you are mid way through investigations, waiting for a procedure, or under the care of a consultant, ask the scheme administrator before your leaving date what happens to that specific authorisation, get the answer in writing, and plan on the answer rather than the hope. Where cover does stop, the practical routes are the NHS, self funding the remainder of that episode, or a new policy which will almost certainly treat the condition as pre-existing.</p>

<h2>Continuation terms, and what they actually preserve</h2>

<p>Many group insurers make a personal policy available to leaving members on what is often called a continuation basis. The point of it is underwriting rather than price. Instead of assessing you from scratch, the insurer carries across your position under the scheme, so conditions that were covered as a member can continue to be covered.</p>

<div class="na-callout-grid">
<div class="na-callout"><h3>What it can preserve</h3><p>Your underwriting position, so conditions covered under the scheme are not suddenly treated as new pre-existing exclusions. That is the whole reason continuation terms are worth investigating.</p></div>
<div class="na-callout"><h3>What it does not preserve</h3><p>The price. Group cover is bought at a corporate rate and often subsidised by the employer. A personal policy is priced on your age and circumstances.</p></div>
<div class="na-callout"><h3>What it may not preserve</h3><p>The benefit structure. Hospital lists, outpatient limits and excesses on the personal product may not match the scheme, so cover can be narrower even where the underwriting carries over.</p></div>
<div class="na-callout"><h3>Whether it is offered at all</h3><p>Continuation is a feature of the scheme and the insurer, not a right. Eligibility can depend on how long you were a member and why you are leaving.</p></div>
</div>

<h2>Moving to a different insurer</h2>

<p>Continuation terms usually point you at the same insurer\'s personal range. Where you want to move elsewhere, the equivalent mechanism is continued personal medical exclusions, sometimes called a switch or transfer basis. The new insurer looks at the terms that applied to you before and broadly applies the same exclusions rather than underwriting you afresh.</p>

<p>This is at the receiving insurer\'s discretion. They will want evidence of your previous cover, the underwriting basis and any exclusions, and they may decline, apply additional exclusions or ask for medical information anyway. The more complete your documentation from the scheme, the better the chance of a clean transfer.</p>

<h2>Deadlines matter more than anything else</h2>

<p>Continuation and transfer arrangements are almost always time limited and tied to the date membership ended. Miss the window and the option normally disappears, at which point the only route is a new policy underwritten in the ordinary way. There is no mechanism for reviving an option on cover that has already ended.</p>

<p>A gap creates the same problem even inside the window, because continuity of cover is what the whole arrangement is built on. In practical terms, the work needs to start well before your last day, not after it.</p>

<h2>If continuation is not available or not taken</h2>

<p>A new personal policy brings the ordinary rules back into play. You choose between moratorium underwriting, where recent conditions are excluded until you complete a continuous clear period, and full medical underwriting, where you declare your history and receive written exclusions before you commit. Either way, a condition covered under a medical history disregarded scheme may well not be covered now. That is the real cost of losing scheme cover. Other options people consider include a health cash plan, which reimburses everyday costs such as dental and optical treatment within limits rather than funding private surgery, or relying on the NHS. These are different things doing different jobs, not substitutes for one another.</p>

<h2>Family members on the scheme</h2>

<p>If your partner or children were covered as your dependants, their cover ends when yours does. Their underwriting position is separate from yours, so a continuation arrangement needs to address each person. Where a partner has access to their own employer\'s scheme, adding dependants there may be possible, though it usually depends on that scheme\'s rules and its own joining windows.</p>

<h2>Tax and payroll</h2>

<p>Employer paid medical cover is normally a taxable benefit in kind, reported to HMRC and reflected in your tax code, so your take home pay already accounted for it. When the benefit stops, your tax code will usually be adjusted at some point, which can affect your net pay in either direction while HMRC catches up. How this works out depends on your circumstances and your overall tax position.</p>

<h2>Other benefits that end at the same time</h2>

<p>Medical cover is rarely the only employer benefit that stops. It is worth establishing what else is ending, because the gaps are not interchangeable.</p>

<ul>
<li>Group life cover, often called death in service, pays a lump sum to your beneficiaries if you die while employed. It pays on death only and provides nothing if you are alive but unable to work.</li>
<li>Group income protection, where it exists, pays a continuing benefit during long term incapacity, usually after a deferred period and subject to its own incapacity definition.</li>
<li>Group critical illness, employee assistance programmes, virtual GP services and health screening typically end with employment too.</li>
<li>Contractual sick pay ends. Statutory Sick Pay is a legal minimum, and where an employer pays contractual sick pay it normally includes the statutory element rather than following on after it. A new employer\'s terms may be considerably less generous.</li>
</ul>

<p>Medical cover is the benefit most likely to offer a route to a personal policy, but it is not the only one. Some group life schemes include a continuation option, allowing an individual policy with the same insurer without full medical underwriting, usually within a short window after leaving and at individual rates. Group income protection and group critical illness rarely offer an equivalent. Ask the scheme administrator what each scheme offers before your last day, because where no option exists, replacing the benefit means a new application and fresh underwriting.</p>

<h2>Redundancy, retirement and long term sickness</h2>

<p>The route out of employment can change what is available. Some schemes treat retiring members differently from resigning members, and some make continuation available on redundancy where it might not otherwise apply. If you are leaving while unwell or in receipt of a group income protection benefit, the position is more complicated again, because the scheme rules may keep some benefits running while others stop. These are questions for the scheme administrator and the insurer.</p>

<h2>What an adviser will want to understand</h2>

<ul class="na-checklist">
<li>Your exact date of leaving and the date scheme membership actually ends.</li>
<li>The insurer, the scheme number and the underwriting basis that applied to you.</li>
<li>Whether the scheme offers continuation terms, and the deadline for taking them up.</li>
<li>Any treatment, referral or investigation currently under way, and whether it has been authorised.</li>
<li>Which family members were covered and whether they need cover in their own right.</li>
<li>Your medical history, including anything that arose while you were a scheme member.</li>
<li>Whether a new employer offers a scheme, when you can join it, and on what basis.</li>
<li>What the scheme gave you that you actually valued, such as a particular hospital list or outpatient access.</li>
<li>What ongoing cost is realistic now that the cover is no longer employer funded.</li>
<li>What other employer benefits are ending at the same time.</li>
</ul>

<p class="na-disclaimer">This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.</p>
</div></section>');
    }

    public static function guide_life_insurance_and_trusts(): string
    {
        return self::html('<section class="na-section"><div class="na-shell na-prose">
<nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a> <span aria-hidden="true">/</span> <a href="/guides/">Guides</a> <span aria-hidden="true">/</span> <span>Life insurance and trusts</span></nav>

<p class="na-eyebrow">Policy ownership</p>

<p class="na-lede">A trust decides who controls a life insurance policy and who is intended to benefit from it. It is a legal arrangement in its own right, not a tick box on an application form.</p>

<h2>What a trust actually does</h2>

<p>A trust separates legal ownership from beneficial entitlement. When a policy is written in trust, or an existing policy is placed into trust, the person who set it up gives up ownership to trustees, who then deal with the insurer and apply the proceeds of a valid claim under the trust deed. The intended recipients hold a beneficial interest rather than the policy itself.</p>

<p>Three practical effects usually follow. The policy no longer forms part of the estate in the way it otherwise would, because it is no longer the settlor\'s to leave. The insurer has a clear set of people to pay. And trustees, rather than an executor dealing with an entire estate, become responsible for that particular sum of money.</p>

<p>None of this changes the underlying cover. The sum assured, the exclusions and the conditions for a valid claim all sit in the policy, not the trust, and a trust cannot make an invalid claim payable.</p>

<h2>What a trust does not do</h2>

<ul>
<li>A trust does not guarantee a particular tax outcome. It changes how the money is held, which can change how it is treated, but the treatment depends on the type of trust, the value involved, individual circumstances and the rules in force at the time, all of which can change.</li>
<li>A trust does not replace a will. It deals with one asset, and the rest of the estate still has to be dealt with properly.</li>
<li>A trust does not fix an unsuitable policy. If the cover is the wrong shape, the trust simply delivers the wrong amount to the right people.</li>
</ul>

<h2>Settlor, trustees and beneficiaries</h2>

<div class="na-callout-grid">
<div class="na-callout"><h3>The settlor</h3><p>The person who creates the trust and puts the policy into it, usually the life assured. Once the trust is made, the settlor has given the policy away. Any benefit retained can undermine the arrangement and has its own tax consequences.</p></div>
<div class="na-callout"><h3>The trustees</h3><p>The people who legally own and administer the policy. They deal with the insurer, submit the claim, hold the money and pay it out under the deed. They owe duties to the beneficiaries and must act in their interests, not their own.</p></div>
<div class="na-callout"><h3>The beneficiaries</h3><p>The people, or class of people, the money is intended for. Depending on the trust, they may have a fixed entitlement or only the possibility of benefiting at the trustees\' discretion.</p></div>
</div>

<p>The settlor is normally also a trustee, which is why more than one trustee is usually appointed. If the only trustee is the person covered, nobody is left to act at the moment the policy pays out. Providing for replacements, if a trustee dies, moves abroad or loses capacity, is one of the most commonly overlooked parts of setting a trust up. Choose people who will still be contactable, competent and willing years later.</p>

<h2>Bare and discretionary trusts in general terms</h2>

<p>Insurers usually offer a small number of standard trust forms, and most fall into two broad families.</p>

<p>A bare, or absolute, trust names the beneficiaries and fixes their shares from the outset, so they are absolutely entitled. That certainty is the attraction: everybody knows who gets what, and the trustees have little to decide. The drawback is the same certainty. The beneficiaries generally cannot be changed later, so a relationship that ends, a beneficiary who dies first, or a family that grows later can produce an outcome the settlor would not have wanted. A beneficiary who reaches the relevant age of entitlement can normally require the money to be handed over.</p>

<p>A discretionary, or flexible, trust names a class of potential beneficiaries and leaves the trustees to decide who benefits, in what proportions and when. That flexibility suits circumstances that may change, young children, or a settlor who wants trustees to respond to a beneficiary\'s situation at the time. The trade-off is that no potential beneficiary has a guaranteed entitlement, the trustees carry real responsibility, and this family of trusts sits within a tax regime of its own. Transfers into and out of such trusts, and charges arising at intervals set by the rules, may be relevant depending on the values involved.</p>

<p>Most insurer trusts also allow a letter of wishes: a non-binding note telling the trustees how the settlor would like the discretion exercised. It carries no legal force, but it is among the most useful documents a settlor can leave, and it can be updated without changing the deed.</p>

<h2>Why the choice is difficult to reverse</h2>

<p>Placing a policy into trust is a gift, and it cannot simply be undone because the settlor has changed their mind. A bare trust in particular is difficult to unwind, because the named beneficiaries already own their share. Some flexible trusts allow beneficiaries to be added or removed, but only within the limits set by the deed and only by the people the deed gives that power to.</p>

<p>This is why the drafting deserves attention before signing rather than after. Common regrets involve naming a partner rather than a class that would include a future partner, forgetting children born later, appointing a single trustee, or using a trust form meant for one purpose on a policy bought for another. Putting a mortgage protection policy in trust for anyone other than the person who will still owe the debt can create the very problem the cover was bought to solve.</p>

<h2>Probate and the speed of payment</h2>

<p>If a policy is not in trust and the policyholder dies, the proceeds generally form part of the estate, and the insurer will usually want to see a grant of probate, or a grant of confirmation in Scotland, before paying a substantial sum. Obtaining a grant takes time that is outside the family\'s control, and meanwhile the bills, the mortgage payments and the funeral costs do not pause.</p>

<p>Where the policy is held on trust, the trustees own it already, so subject to the insurer\'s claim requirements the claim can usually be dealt with without waiting for the estate to be administered. That is often the most immediately valuable feature of a trust, and one of the few that does not depend on the tax position at all. It is not instant, but it removes an unpredictable delay.</p>

<h2>Consequences that reach beyond the policy</h2>

<p>A trust is an estate planning decision, so it interacts with matters that have nothing to do with insurance. Money held for a beneficiary may be relevant to their own circumstances, including means-tested support or a divorce settlement. Trustees may also have reporting and registration obligations, which attach to the trust rather than to the policy.</p>

<p>The inheritance tax position has to be considered in the round rather than policy by policy. The basis on which a person is connected to the UK for inheritance tax purposes changed in April 2025, moving away from the old domicile test to a test based on long-term UK residence, so older material can be misleading. Reliefs are also often narrower than people expect. Taper relief, for instance, reduces the tax payable on a gift that fails because the person did not survive the required period, and it only matters where the gift exceeded the nil rate band available to it. Below that there is no tax to taper.</p>

<p>Tax treatment depends on individual circumstances and on current rules, and both can change. None of this is tax or legal advice. For anything beyond a straightforward insurer trust form, a solicitor or tax adviser should be involved alongside the insurance advice, and their work co-ordinated with the will rather than run separately from it.</p>

<h2>Keeping the arrangement alive</h2>

<p>A trust that nobody can find does not help anybody. Keep the deed with the policy documents, make sure the trustees know where the paperwork is, and check that the insurer holds a record of the trust.</p>

<h2>What an adviser will want to understand</h2>

<ul class="na-checklist">
<li>Who the cover is really for, and who would need money quickly if a claim arose</li>
<li>Whether the policy is meant to clear a debt, replace income or pass on capital, since that changes who should benefit</li>
<li>Your family circumstances, including dependants, previous relationships and anyone you would not want to benefit</li>
<li>Whether there is a will, what it says, and who is dealing with the wider estate</li>
<li>Who you would trust to act, and who could step in if they could not</li>
<li>Whether an existing policy is already in trust, and under what form of deed</li>
<li>Whether a solicitor or tax adviser is involved, or should be</li>
<li>How flexible you need the arrangement to be, given that it is hard to reverse</li>
</ul>

<p class="na-disclaimer">This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.</p>
</div></section>');
    }

    public static function guide_life_insurance_vs_critical_illness_cover(): string
    {
        return self::html('<section class="na-section"><div class="na-shell na-prose">
<nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a> <span aria-hidden="true">/</span> <a href="/guides/">Guides</a> <span aria-hidden="true">/</span> <span>Life or critical illness</span></nav>
<p class="na-eyebrow">Personal protection</p>
<p class="na-lede">Life insurance and critical illness cover are often presented side by side because both can pay a lump sum. They are not alternatives to one another. They respond to different events, are assessed in different ways, and the money usually ends up in different hands.</p>

<h2>The question is not which is better, it is which event you are covering</h2>
<p>Life insurance is designed to pay when the insured person dies during the policy term, provided the policy is in force and the terms are met. Critical illness cover is designed to pay when the insured person is diagnosed with, or undergoes a procedure for, one of the conditions listed in that policy, and the diagnosis meets the definition the insurer has written for it.</p>
<p>That distinction drives everything else. A death claim is largely a question of fact once the death certificate and policy ownership are confirmed. A critical illness claim is a question of medical interpretation, because the insurer has to test the diagnosis against a written definition. Two people can have the same illness label from their consultant and reach different outcomes, because the policy wording turns on severity, on the procedure carried out, or on findings from a particular test.</p>

<h2>What critical illness definitions actually do</h2>
<p>A critical illness policy is not cover for being seriously ill. It is cover for a defined list of conditions, each described in technical terms. Insurers in the UK commonly use, or build on, the model wordings and the statement of best practice published by the Association of British Insurers, which set a minimum standard for the core conditions and for how they are described. Insurers can and do offer definitions that go further, and they can add conditions that sit outside the model set.</p>
<p>Some features to look for in the wording rather than the marketing summary:</p>
<ul>
  <li>Severity thresholds. Many definitions require a stated level of permanent damage, a particular grade or stage, or a specified clinical finding before the condition counts.</li>
  <li>A survival period. Most policies require the insured person to survive for a short defined period after diagnosis before the benefit becomes payable. The length is set out in the policy.</li>
  <li>Partial or additional payments. Many policies pay a reduced amount for certain less severe conditions or early stage diagnoses. That payment may reduce the remaining sum assured or may sit on top of it, depending on the policy.</li>
  <li>Total permanent disability. Where this is included, it is normally assessed against a work-based or activity-based test, and the test used matters a great deal.</li>
  <li>Children\'s cover. Many policies include cover for the policyholder\'s children, usually with its own conditions, age limits and payment basis.</li>
  <li>Exclusions and personal terms. Underwriting may exclude a condition connected to your medical history, or apply other individual terms.</li>
</ul>
<p>Because definitions differ between insurers, and between generations of the same insurer\'s product, comparing on price alone tells you very little. A cheaper policy can use narrower wording on exactly the conditions most relevant to your family history.</p>

<h2>Life cover is simpler to trigger, but the design questions are not</h2>
<p>Most life policies also include some form of terminal illness benefit, which can allow the sum assured to be paid early where the insured person is diagnosed with an illness expected to be terminal within the period the policy defines. That is not a substitute for critical illness cover, because it responds near the end of life rather than at diagnosis of a serious but survivable condition.</p>
<p>The larger design questions with life cover are about shape and ownership. A level term policy keeps the sum assured constant. A decreasing policy is designed to reduce over the term and is often discussed alongside a repayment mortgage, although the rate at which cover falls will not necessarily track your actual balance. Family income benefit pays a regular amount for the remainder of the term instead of a single lump sum. Whole of life cover has no fixed end date and is usually considered for a different purpose again.</p>

<h2>Who gets the money, and why it changes the structure</h2>
<p>With critical illness cover, the insured person is normally alive and usually wants access to the money themselves, to reduce hours, adapt a home, fund care, or take the pressure off while treatment happens. With life cover the insured person is not there, so the question becomes who receives the benefit and how quickly.</p>
<p>That is why life cover is frequently written under trust and critical illness cover often is not, or is written under an arrangement that splits the two. A trust is a legal arrangement and its suitability, its wording and its tax treatment depend on your circumstances and on current rules, both of which can change. Legal or tax advice may be needed before anything is signed.</p>

<div class="na-callout-grid">
  <div class="na-callout"><h3>Term</h3><p>Cover normally runs for a fixed period. Choosing a term that ends before your commitments do leaves a gap at exactly the age when new cover is hardest to arrange.</p></div>
  <div class="na-callout"><h3>Premium basis</h3><p>Guaranteed premiums cannot be changed by the insurer because of claims experience. Reviewable premiums can be reassessed under the conditions set out in the policy.</p></div>
  <div class="na-callout"><h3>Indexation</h3><p>An option to increase cover over time, normally with a corresponding increase in premium. Without it, the real value of a fixed sum assured erodes across a long term.</p></div>
  <div class="na-callout"><h3>Waiver of premium</h3><p>An option on many policies that can maintain premiums if illness or injury stops you working, subject to its own definition and waiting period.</p></div>
</div>

<h2>Joint or single lives, and one point that is often misunderstood</h2>
<p>A joint life first death policy covers two people and pays once, on the first death. It then ends. There is no residual cover for the survivor once the policy has paid. A small number of insurers offer a survivor option, allowing the surviving life to apply for individual cover without full medical underwriting inside a short window after a claim, but it is not a standard feature and it should be confirmed in the policy wording rather than assumed. A separation option is a different feature again, dealing with the end of a relationship rather than a death claim. Where no such option exists, new cover after a claim is a fresh application, on the health and the price available at that time.</p>
<p>Two single life policies usually cost more, but each life keeps its own cover after a claim, each can be placed under its own trust, and the two can be varied independently. The same reasoning applies to critical illness cover: a joint arrangement that pays on the first qualifying diagnosis leaves the second person without cover afterwards.</p>

<h2>Honesty at application is part of the cover</h2>
<p>Under the Consumer Insurance (Disclosure and Representations) Act 2012 you have a duty to take reasonable care not to make a misrepresentation when you apply. In practice that means answering the insurer\'s questions fully and accurately, including matters you may consider minor or embarrassing, and correcting anything that changes before the policy starts. Careless or deliberate misrepresentation can affect the outcome of a claim, and it is usually discovered at the worst possible moment. If you are unsure whether something is relevant, disclose it and let the underwriter decide.</p>

<h2>Where income protection sits between the two</h2>
<p>Neither life cover nor critical illness cover is designed to replace an income. Critical illness cover pays on a listed diagnosis whether or not you stop working. Income protection pays a regular benefit when illness or injury stops you working, whatever the diagnosis, including the back problems and mental health conditions that keep many people off work but rarely appear on a critical illness list. A lump sum and a monthly income solve different problems, and many conversations end up allocating a limited budget between them rather than choosing one outright.</p>

<h2>When budget forces a choice</h2>
<p>If everything cannot be covered, the useful question is which event would do the most financial damage soonest, and what already exists to soften it. A death in service benefit from an employer pays only on death and does nothing at all if you are ill and unable to work. Reducing the sum assured, shortening the term or splitting cover between the two products are all ways to fit a budget, and each has a consequence that should be explained to you before you decide.</p>

<h2>What an adviser will want to understand</h2>
<ul class="na-checklist">
  <li>Who depends on your income, and who depends on unpaid work you do at home</li>
  <li>What you already hold personally, through an employer or through a business, and on what terms</li>
  <li>Your mortgage balance, its type, and how long the remaining term is</li>
  <li>Whether a payout would need to clear debt, replace income, fund care, or do more than one of these</li>
  <li>Your full medical history and family history, including anything you are waiting to have investigated</li>
  <li>Your occupation, working pattern and any travel or activities an insurer would ask about</li>
  <li>What premium is genuinely sustainable for the whole term, not just the first year</li>
  <li>Who you would want to receive a benefit, and whether a trust or a will needs revisiting</li>
</ul>

<p class="na-disclaimer">This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.</p>
</div></section>');
    }

    public static function guide_making_a_protection_insurance_claim(): string
    {
        return self::html('<section class="na-section"><div class="na-shell na-prose">
<nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a> <span aria-hidden="true">/</span> <a href="/guides/">Guides</a> <span aria-hidden="true">/</span> <span>Making a claim</span></nav>
<p class="na-eyebrow">Claims and support</p>
<p class="na-lede">A claim usually arrives at the worst possible time, when nobody feels like reading policy documents. Knowing who decides, what they will ask for and what not to do in the meantime takes some of the pressure out of the first few days.</p>

<h2>The insurer decides the claim, not the adviser</h2>
<p>Whoever arranged the policy, the claim is assessed and paid by the insurer named on the policy schedule. Its claims team is the right first contact, and it is the only party that can tell you what evidence applies to your specific policy. An adviser can often help you locate a policy arranged through their firm, explain what the wording means and help you chase, but they cannot approve, decline or accelerate a decision.</p>
<p>Contact the insurer as soon as you reasonably can. Some policies contain notification requirements, and income protection in particular is usually built around a deferred period that runs from a defined date, so an early conversation establishes the clock rather than starting it late.</p>

<h2>Find the policy details</h2>
<p>You need the insurer\'s name and the policy number. Look at the policy schedule, the annual statement, the welcome pack, or the reference on the direct debit line of a bank statement. Email archives are often the fastest route, because policy documents are increasingly issued electronically.</p>
<p>If a policy is believed to exist but cannot be found, work through bank and building society statements for regular payments to an insurer, check paperwork kept with a will or with mortgage documents, and ask the employer\'s HR or payroll team about any death in service or group scheme benefit. Where the policyholder has died, the executors or administrators are usually the right people to make those enquiries.</p>

<h2>Keep paying, and do not cancel anything</h2>
<p>This is the single most common self-inflicted problem. Do not cancel a direct debit while a claim is being considered, and do not cancel or replace an existing policy on the assumption that a claim will succeed. If premiums lapse, cover can end, and that can affect the claim. If a waiver of premium option exists, it usually has its own waiting period, so premiums are normally still due in the meantime. If money is genuinely tight, tell the insurer rather than simply stopping payment.</p>

<h2>What the insurer is likely to ask for</h2>
<div class="na-callout-grid">
  <div class="na-callout"><h3>Life insurance</h3><p>The death certificate, the policy documents, identification, and evidence of who is entitled to receive the benefit. Where the policy is in trust, the trustees claim. Where it is not, a grant of probate or letters of administration may be needed.</p></div>
  <div class="na-callout"><h3>Critical illness cover</h3><p>Medical evidence sufficient for the insurer to test the diagnosis against the policy definition, which can mean consultant reports, test results and histology rather than a GP letter alone.</p></div>
  <div class="na-callout"><h3>Income protection</h3><p>Evidence of incapacity, of your occupation and duties, and of earnings. Insurers usually reassess continuing eligibility during a claim rather than only at the start.</p></div>
  <div class="na-callout"><h3>Private medical insurance</h3><p>Pre-authorisation is normally required before eligible private treatment begins, together with a GP referral and details of the proposed treatment and provider.</p></div>
</div>

<h2>Medical evidence and your rights</h2>
<p>Most protection claims involve the insurer seeking medical information, and you will be asked to give consent. Read the consent form rather than signing automatically, because the scope varies: some request a targeted report, others request full records.</p>
<p>Where an insurer asks your own doctor for a medical report, the Access to Medical Reports Act 1988 gives you rights, including the right to say you want to see the report before it is sent, and to ask the doctor to amend anything you believe is factually incorrect. Exercising that right may add a little time, so weigh it against the urgency of the claim.</p>
<p>Do not send medical records, diagnoses or other health information through a website enquiry form. Sensitive information should go directly to the insurer through the route its claims team specifies.</p>

<h2>Why claims take time, and what usually causes delay</h2>
<p>Insurers depend on third parties who work to their own timescales: GP practices, hospital records departments, employers, coroners and, where probate is needed, the Probate Registry. The insurer cannot compel any of them to be quick. In most cases the useful thing you can do is provide your part quickly and completely.</p>
<ul>
  <li>Incomplete claim forms, particularly missing dates, GP details or signatures on consent.</li>
  <li>Waiting for medical records or consultant reports.</li>
  <li>Occupational or financial evidence outstanding on an income protection claim.</li>
  <li>Uncertainty about who legally owns the policy, or trustee details that were never kept up to date.</li>
  <li>Probate, where a life policy was not written under trust and forms part of the estate.</li>
</ul>
<p>Ask the claims handler what is currently outstanding, who they are waiting on, and when they expect to review the file again. Ask specifically whether an interim or partial payment is possible where a claim is accepted in principle but a final figure is unresolved.</p>

<h2>Keep a record from the first call</h2>
<ul class="na-checklist">
  <li>The claim reference, and the name of the person you spoke to each time</li>
  <li>The date of each call and what was agreed or requested</li>
  <li>Copies of every form and document you send, and the date you sent it</li>
  <li>Any deadline the insurer sets, and any deadline you set for a promised update</li>
  <li>Which relative, executor or trustee is dealing with the insurer, so contact stays consistent</li>
</ul>

<h2>If a claim is declined or you are unhappy with the handling</h2>
<p>Ask the insurer for its decision in writing, with the specific policy wording it relies on. A decline is not always the end of the matter: it may rest on evidence that can be supplemented, on a definition point that is arguable, or on a factual misunderstanding about your job or your medical history.</p>
<p>If you remain dissatisfied, use the insurer\'s formal complaints process. Firms must handle complaints under Financial Conduct Authority rules and issue a final response. If you are still unhappy after that, or the firm takes too long, you can normally refer the complaint to the Financial Ombudsman Service, which is free to consumers. There is a time limit for referring a complaint after a final response, and it is stated in the response letter, so read that letter carefully and act within it. Insurers authorised in the UK are also covered by the Financial Services Compensation Scheme, subject to its eligibility rules and limits.</p>

<h2>Support beyond the money</h2>
<p>Many insurers provide practical support alongside the benefit, such as bereavement or counselling services, second medical opinion services, or rehabilitation help on an income protection claim. These are often included rather than optional, and are frequently forgotten. Ask what is available under the policy, and whether family members can use it too.</p>

<h2>What an adviser will want to understand</h2>
<ul class="na-checklist">
  <li>Which insurer the policy is with, and the policy number if you have it</li>
  <li>The type of cover involved, and roughly when it was arranged</li>
  <li>Whether the policy is written under trust, and who the trustees are</li>
  <li>Who is dealing with matters, and whether they are an executor, trustee or family member</li>
  <li>Whether the insurer has already been contacted, and what stage the claim has reached</li>
  <li>What the insurer has asked for that you are struggling to obtain</li>
  <li>Whether premiums are still being paid, and whether any waiver option applies</li>
  <li>Whether other cover exists, including workplace benefits that nobody has checked yet</li>
</ul>

<p class="na-disclaimer">This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.</p>
</div></section>');
    }

    public static function guide_preparing_for_protection_appointment(): string
    {
        return self::html('<section class="na-section"><div class="na-shell na-prose">
<nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a> <span aria-hidden="true">/</span> <a href="/guides/">Guides</a> <span aria-hidden="true">/</span> <span>Appointment checklist</span></nav>
<p class="na-eyebrow">Appointment checklist</p>
<p class="na-lede">Preparation changes what an appointment is for. With the basics gathered in advance, the time goes on the choices that actually matter to you rather than on hunting for a policy number or guessing at a mortgage balance.</p>

<h2>What actually happens in a protection appointment</h2>
<p>A protection conversation normally follows a recognisable shape. The adviser explains who they are, what services they provide, which insurers they can advise on and how they are paid. They gather information about your circumstances, commitments, health and objectives, a process usually called a fact find. They discuss what could go wrong and what already exists to cushion it. They then explain options and, if advice is given, why a particular arrangement is considered suitable for you, normally confirmed in writing.</p>
<p>Applications go to underwriting after that, not before, and the terms an insurer offers are the insurer\'s decision. That sequence is worth understanding, because it explains why an adviser will not promise a price at the first meeting.</p>

<h2>Gather your existing safety net</h2>
<p>Almost every avoidable problem in these conversations comes from cover people forget they have, or think they have and do not.</p>
<ul>
  <li>Policy schedules for any personal life, critical illness, income protection or private medical cover, plus any trust deeds.</li>
  <li>Your employer\'s written sick pay terms, from the contract or handbook, including whether entitlement depends on length of service.</li>
  <li>Details of any death in service benefit, group income protection or group medical scheme, from the scheme documents rather than from memory.</li>
  <li>Pension scheme death benefits, and whether the expression of wish or nomination form is up to date.</li>
  <li>Cover held by a partner, and any policy owned by a business you are involved in.</li>
  <li>Accessible savings, and any that are earmarked for something else.</li>
</ul>
<p>Two cautions worth carrying into the meeting. A death in service benefit pays on death only and does nothing if illness stops you working. Group benefits belong to the employer and can be changed or withdrawn, and they usually stop when the employment does.</p>

<h2>Map your commitments honestly</h2>
<p>The purpose of this section is to establish what would still have to be paid if your income stopped, and for how long.</p>
<ul>
  <li>Mortgage balance, whether it is repayment or interest only, the remaining term and the end date.</li>
  <li>Loans, credit agreements, car finance and any personal guarantees.</li>
  <li>Essential monthly spending, separated from discretionary spending. Bank statements are more reliable than estimates.</li>
  <li>Childcare, school fees or care costs, and how long each would continue.</li>
  <li>Who relies on you, including anyone relying on unpaid work such as childcare or caring, which has a real replacement cost.</li>
  <li>Business borrowing or ownership arrangements, if relevant.</li>
</ul>

<h2>Be ready to talk about health</h2>
<p>This is the part people prepare for least and it has the largest effect on outcome. Under the Consumer Insurance (Disclosure and Representations) Act 2012 you must take reasonable care not to make a misrepresentation when you apply. In practice, insurers ask detailed questions and expect complete answers.</p>
<p>Have to hand your GP practice details, an outline of past conditions, investigations and referrals with approximate dates, current medication, height and weight, smoking or vaping history including when you stopped, and typical alcohol consumption. Mention anything currently under investigation, even without a diagnosis, and anything an insurer has previously declined, postponed or excluded. Family history of serious illness is commonly asked about too.</p>
<p>Underwriting may result in standard terms, or in an increased premium, an exclusion, a postponement or a decline. None of that is unusual, and knowing about it in advance turns a surprise into a decision. Insurers may also ask for a GP report or a medical examination, which affects how long the process takes.</p>
<p>Share sensitive health detail directly with the adviser or insurer. Do not enter it into a website enquiry form.</p>

<h2>Think about who should receive the money</h2>
<p>Bring the names, dates of birth and relationships of the people you would want to benefit, and be ready to say who you would trust to administer money on behalf of children. If you have a will, note when it was last reviewed, and note any change in circumstances since, such as marriage, divorce, separation, a new child or a death.</p>
<p>Trusts and beneficiary arrangements have legal and tax consequences that depend on your individual circumstances and on current rules, both of which can change. Legal or tax advice may be needed, and an adviser should tell you where that line sits rather than crossing it.</p>

<h2>Decide your budget before you are asked</h2>
<p>Work out what you could sustain comfortably every month for the whole term, not just what looks affordable today. Cover that lapses in a few years because the premium was set at the limit of what you could manage provides nothing when it is needed. If the budget will not stretch to everything, say so early, because prioritising deliberately produces a better result than trimming at the end.</p>
<p>It also helps to think about the direction your circumstances are heading. A move, a new child, a business you intend to start, a partner returning to work or a mortgage you plan to overpay all change what the cover needs to do. An adviser can only take account of what you tell them, and plans a year away are still relevant to a decision made today, particularly where the term or the shape of cover would otherwise be set around today\'s position alone.</p>

<h2>Questions worth asking</h2>
<div class="na-callout-grid">
  <div class="na-callout"><h3>Purpose</h3><p>What specific financial problem is this arrangement meant to solve, and what would happen if the event occurred tomorrow?</p></div>
  <div class="na-callout"><h3>Definitions</h3><p>Which wordings, exclusions and claim definitions in this policy matter most given my job and my medical history?</p></div>
  <div class="na-callout"><h3>Cost over time</h3><p>Are the premiums guaranteed, reviewable or age related, and what could change the amount I pay later?</p></div>
  <div class="na-callout"><h3>Service and payment</h3><p>Which insurers can you advise on, how are you paid, and what happens to that if I cancel early?</p></div>
</div>

<h2>What happens after the meeting</h2>
<p>Expect to receive written information about the recommendation and the product, including the insurer\'s policy summary and the reasons for any advice given. Read it while it is fresh and query anything that does not match your understanding of the conversation.</p>
<p>Do not cancel existing cover until any new policy is confirmed as on risk. Replacing cover can mean new underwriting, new exclusions and a fresh start on any waiting periods, and a gap between the two is the point of maximum exposure. Ask directly what would be lost by replacing what you already hold.</p>
<p>A cancellation period applies to new pure protection policies and the length is set out in your documents. Check it, and check when premiums and cover actually start, because those dates are not always the same.</p>

<h2>What an adviser will want to understand</h2>
<ul class="na-checklist">
  <li>Your household make-up, dependants and anyone who relies on unpaid work you do</li>
  <li>Income for everyone in the household, and how it is made up</li>
  <li>Mortgage and other borrowing, with balances, terms and end dates</li>
  <li>Essential monthly outgoings and accessible savings</li>
  <li>Every existing policy and workplace benefit, from the documents rather than from memory</li>
  <li>Your occupation, duties, working pattern and any hazardous activities or travel</li>
  <li>Full medical and family history, including anything under investigation</li>
  <li>Who you want to benefit, and whether wills, nominations or trusts need revisiting</li>
  <li>A monthly budget you could sustain for the full term</li>
  <li>Anything that would make communication easier for you, including support needs or a preferred contact method</li>
</ul>

<p class="na-disclaimer">This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.</p>
</div></section>');
    }

    public static function guide_relevant_life_vs_key_person_cover(): string
    {
        return self::html('<section class="na-section"><div class="na-shell na-prose">
<nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a> <span aria-hidden="true">/</span> <a href="/guides/">Guides</a> <span aria-hidden="true">/</span> <span>Relevant life or key person</span></nav>

<p class="na-eyebrow">Business protection</p>

<p class="na-lede">Relevant life cover and key person cover are often mentioned in the same conversation because a company pays for both. That is where the similarity ends. They protect different people from different problems, and the money ends up in different hands.</p>

<h2>Two different problems</h2>

<p>Relevant life cover answers a question about an employee\'s family. If this director or employee dies, what does the business want their household to receive? It is an employer-arranged death-in-service benefit for one individual, and the money is intended for their dependants.</p>

<p>Key person cover answers a question about the business. If we lose this person, what happens to our profit, our projects, our lending and our ability to keep trading? The money is intended for the company, to absorb the disruption.</p>

<p>Get those two questions the wrong way round and the cover still pays, but it pays the wrong party. A family cannot claim on a key person policy, and a company cannot use relevant life proceeds to plug a hole in its own accounts. The structure decides that, not the intention behind it.</p>

<h2>Who owns the policy and who receives the money</h2>

<div class="na-callout-grid">
<div class="na-callout"><h3>Relevant life ownership</h3><p>The employer applies for the policy and pays the premiums, but the policy is written under a suitable trust from the outset. The trustees, not the business, deal with a claim.</p></div>
<div class="na-callout"><h3>Relevant life benefit</h3><p>The trustees hold the proceeds for the employee\'s family or dependants under the trust wording. The business has no entitlement to the money.</p></div>
<div class="na-callout"><h3>Key person ownership</h3><p>The business applies for, owns and pays for the policy on the individual\'s life, with that person\'s consent. No trust is involved in the usual arrangement.</p></div>
<div class="na-callout"><h3>Key person benefit</h3><p>The business receives the proceeds and decides how to use them, subject to any commitment given to a lender or to other shareholders.</p></div>
</div>

<h2>Relevant life cover in more detail</h2>

<p>A relevant life policy is a single life arrangement, so it suits businesses too small to run a group death-in-service scheme, or those wanting a benefit for one or two individuals rather than the whole workforce. It is commonly considered by owner-managed limited companies for their working directors.</p>

<p>Because the arrangement relies on conditions set out in the tax rules, it is more constrained than a personal life policy. Those rules limit what can sit inside it, so features a client might expect from a personal plan are not necessarily available, and what is offered varies by provider. The only benefit a relevant life policy can provide is a lump sum on death, and terminal illness cover is generally accepted as an acceleration of that death benefit. Critical illness cover cannot be included, and the policy can have no surrender value. Cover has to end before a specified age, the proceeds must be capable of reaching an individual or a charity rather than the business, and the arrangement must not exist for tax avoidance purposes. Anything beyond that basic shape needs checking against the provider\'s terms and the trust deed.</p>

<p>Eligibility is the first thing to test. The person covered must be an employee, which for these purposes generally includes salaried directors. Sole traders are not employees of their own business, and equity partners are generally in the same position, so this route is often unavailable to them.</p>

<p>The trust is not an optional extra. It is part of what makes the arrangement work, so the same considerations apply as to any life policy trust: who the trustees are, whether the class of beneficiaries reflects the individual\'s actual family, and whether a letter of wishes has been left. The person covered should know the arrangement exists and who the trustees are.</p>

<h2>Key person cover in more detail</h2>

<p>Key person cover starts with identifying who the business genuinely depends on, which is not always the person with the largest shareholding or the grandest title. The test is practical: whose absence would cost money, and how quickly would that show up? That might be the person holding the client relationships, the only individual with a particular accreditation, or the director whose involvement a lender relies on.</p>

<p>Cover can be arranged on death alone or with critical illness cover added. Serious illness often creates a longer and more disruptive absence than a death, because the business waits, hopes and delays hiring. The critical illness definitions in the policy decide what actually pays, and they vary considerably between insurers, so the wording matters more than the label.</p>

<p>The amount should be reasoned and documented. Businesses commonly consider the individual\'s contribution to gross profit, the cost of recruiting and training a replacement, the time to bring them up to speed, and any borrowing or contractual commitment at risk. Underwriters may ask how the figure was reached, particularly where the sum is large relative to the business, so a documented rationale helps.</p>

<h2>Tax treatment is not automatic for either</h2>

<p>This is where general commentary is least reliable, and where a company\'s accountant should be involved before anything is put in place.</p>

<p>For key person cover, whether premiums are a deductible business expense, and whether a claim receipt is taxable, depend on the purpose of the policy, the role and any shareholding of the person covered, and the length of the cover. The position is generally viewed differently where the policy protects profits than where it protects a capital item such as a loan, and there is no single answer that applies to every company.</p>

<p>For relevant life cover, the treatment of premiums for the company and for the individual depends on the arrangement meeting the conditions in the rules and on the company\'s own circumstances. Because a relevant life policy is not a registered pension scheme arrangement, the benefit is generally outside the allowances that apply to registered scheme death benefits. Those pension allowances have changed in recent years, which is one reason older summaries of relevant life cover can be misleading. Older articles on this subject can therefore be out of date.</p>

<p>Tax treatment depends on individual circumstances and on current rules, and both can change. This guide is not tax or legal advice, and neither arrangement should be set up on the strength of a general description alone. Legal advice on the trust or on any related agreement may also be needed alongside the insurance advice.</p>

<h2>What happens when the person leaves</h2>

<p>People move on, and neither arrangement follows them automatically.</p>

<p>A key person policy is owned by the business. If the individual leaves, the business no longer has the exposure it insured, and continuing to pay for the cover may make little sense. Equally, the person who replaced them may now be the key person and may not be covered at all. It is a straightforward review point that is very commonly missed.</p>

<p>A relevant life policy depends on an employment relationship, so where that relationship ends the basis of the arrangement changes. Some providers allow the policy to be continued or transferred in defined circumstances, but this varies by provider and is governed by the policy terms and the trust wording, so check it at outset rather than assume it when it is needed.</p>

<h2>Where the two get confused</h2>

<ul>
<li>Expecting a relevant life policy to pay the business. It cannot. The benefit is held on trust for the individual\'s dependants.</li>
<li>Using key person cover as an informal family benefit. The proceeds belong to the business, and its creditors, other shareholders and lenders may have a claim on them before the family sees anything.</li>
<li>Assuming relevant life cover is available to everyone in the business, when the eligibility conditions may exclude sole traders and equity partners.</li>
<li>Treating either as a substitute for shareholder protection. Neither transfers ownership of a shareholding, and neither replaces the legal agreement that makes a share purchase happen.</li>
</ul>

<h2>They are not alternatives</h2>

<p>The question in the title is a common one, but for many businesses the honest answer is that the two do different jobs and both may be relevant. A working director might be a key person whose loss would damage profits, and also an employee whose family would need a death-in-service benefit. Those are separate exposures, sized differently, owned differently and paid to different people. In a business with more than one owner there is usually a third conversation to have, about shareholder or partnership protection, which neither of these arrangements covers.</p>

<h2>What an adviser will want to understand</h2>

<ul class="na-checklist">
<li>The legal structure of the business and whether the person is an employee, director, partner or sole trader</li>
<li>Whose financial loss the arrangement is meant to address, the family\'s or the company\'s</li>
<li>How any proposed sum has been calculated, and what evidence supports it</li>
<li>What benefits are already provided, including any group scheme or existing policies, and who owns them</li>
<li>Whether other owners are involved and whether a shareholders\' or partnership agreement exists</li>
<li>Who would act as trustees, and whether the intended beneficiaries reflect the person\'s family circumstances</li>
<li>Who the company\'s accountant and solicitor are, since tax and legal advice is needed alongside the insurance advice</li>
<li>What would trigger a review, such as a departure, refinancing or a change in profits</li>
</ul>

<p class="na-disclaimer">This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.</p>
</div></section>');
    }

    public static function guide_types_of_business_protection(): string
    {
        return self::html('<section class="na-section"><div class="na-shell na-prose">
<nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a> <span aria-hidden="true">/</span> <a href="/guides/">Guides</a> <span aria-hidden="true">/</span> <span>Business protection types</span></nav>

<p class="na-eyebrow">Business protection</p>

<p class="na-lede">Business protection is not one product. It is a set of arrangements that each answer a different question about what happens when an owner, a director or an important employee dies or becomes seriously ill. The right starting point is the problem, not the policy.</p>

<h2>Start with the loss, not the cover</h2>

<p>Every business protection arrangement exists to move money to a particular place at a particular moment, so be specific about who suffers the loss. Is it the company, which loses profit or someone it cannot easily replace? The surviving owners, who suddenly share the business with somebody\'s family? A lender, who wants its loan repaid? Or a family, who lose the income of someone employed by the business?</p>

<p>Those are four different losses calling for four different structures. The most expensive mistakes here are rarely about the insurer chosen. They are about cover owned by the wrong person, payable to the wrong party, or written for an amount that no longer reflects what the business is worth.</p>

<h2>Key person cover</h2>

<p>Key person cover protects the business against the disruption of losing someone it depends on: a founder who holds the client base together, a technical lead nobody can replace quickly, or a director whose involvement underpins the company\'s borrowing.</p>

<p>In the usual arrangement the business applies for the policy, owns it, pays the premiums and receives the benefit. The insured person consents but has no entitlement to the money. The proceeds go into the business to absorb lost profit, fund recruitment, cover an interim replacement, or simply buy the remaining directors time.</p>

<p>Sizing it is a judgement rather than a formula, and the reasoning should be written down. Businesses commonly consider the person\'s contribution to gross profit, the cost and timescale of replacing them, and any borrowing that would become difficult without them. The tax treatment is not automatic. Whether premiums are deductible, and whether a receipt is taxable, depend on the purpose of the policy, the role and shareholding of the person covered, and how long the cover runs. The company\'s accountant should confirm the position, since it differs between cover protecting profits and cover protecting a capital item such as a loan.</p>

<h2>Shareholder and partnership protection</h2>

<p>This is ownership succession cover. If a shareholder dies, their shares normally pass under their will to their family, who may want cash rather than shares, while the survivors want control rather than an unfamiliar co-owner. Without an arrangement both sides are stuck: the family cannot easily sell shares in a private company, and the survivors may not have the money to buy them.</p>

<p>Shareholder protection puts money in the hands of the people who need to buy, at the moment the shares become available. The same logic applies to partnerships and limited liability partnerships. Structures differ in who owns the policy and who receives the benefit.</p>

<ul>
<li>Own life in trust. Each owner insures their own life and places the policy in a suitable business trust for their co-owners, so the proceeds reach the survivors, who buy the shares.</li>
<li>Life of another. Each owner insures the lives of the others and owns those policies personally, which becomes unwieldy as the number of owners grows.</li>
<li>Company share purchase. The company owns the policy and buys back the shares itself, subject to company law requirements and its own tax consequences. It needs legal and accountancy input first.</li>
</ul>

<p>Partnerships need particular care, because the partnership agreement, or the absence of one, determines what actually happens on a partner\'s death. Cover written without reading it can conflict with it.</p>

<h2>Why a cross option agreement matters alongside the cover</h2>

<p>Money alone does not complete a transaction. The insurance provides the funds; a separate legal agreement provides the certainty that a sale happens at all, and on what terms. Without one, the survivors hold cash, the family holds shares, and either can refuse.</p>

<p>The usual solution is a cross option, or double option, agreement. Each side gets an option: the survivors can require the estate to sell, and the estate can require the survivors to buy. Because either can force the transaction, the outcome is effectively certain, yet neither is bound to buy or sell from the outset. That distinction is deliberate. An agreement binding both parties to a sale from the start can affect how the shareholding is treated for tax purposes, which is why option-based agreements are the normal approach. Tax treatment depends on individual circumstances and current rules, both of which can change, so this needs a solicitor rather than a template.</p>

<p>Critical illness is usually handled differently in the same agreement, often through a single option exercisable only by the person who is ill, so nobody is forced out of their own business while unwell.</p>

<p>The agreement should also address valuation: how the price is reached, who decides it, and how often it is revisited. Cover set against a valuation that is several years old is one of the most common weaknesses found on review, because growth quietly turns full cover into partial cover. The articles of association and any shareholders\' agreement need checking too, since pre-emption rights and transfer restrictions can conflict with what the arrangement assumes.</p>

<h2>Business loan protection</h2>

<p>Borrowing often outlives the person who arranged it. Commercial mortgages, directors\' loan accounts, invoice finance and personal guarantees can become pressing very quickly after a death or serious illness, and some facility agreements let the lender demand repayment when a key individual leaves.</p>

<p>Business loan protection is cover sized around a specific debt. Where the borrowing reduces over time, cover can reduce alongside it, though the pattern will not track the loan exactly and the basis should be checked. Ownership needs thought. The business usually owns the policy so the proceeds can repay the debt, but where the liability sits with an individual, for example under a personal guarantee, personal ownership with an appropriate trust may fit better. Some lenders ask for the policy to be assigned to them, which gives the lender first call on the proceeds and removes any flexibility over how the money is used. That should be a conscious decision.</p>

<h2>Relevant life cover</h2>

<p>Relevant life cover is an employer-arranged death-in-service benefit for a single employee or director, not cover for the business. The employer applies and pays, but the benefit is held on trust for the employee\'s family or dependants, so the business does not receive the money.</p>

<p>It is often considered by companies too small to run a group death-in-service scheme. The arrangement must satisfy conditions set out in the tax rules, which constrain what can be included, so features available on a personal policy are not necessarily available here. Treatment of the premiums for the company, and of the benefit for the employee, depends on those conditions being met and on the company\'s circumstances, so the accountant should confirm it. The interaction with pension death benefit rules has changed in recent years, which is another reason to take current advice rather than rely on older summaries.</p>

<h2>Executive income protection</h2>

<p>Where key person cover protects the business against losing someone, executive income protection protects an employee\'s earnings through the business. The employer typically owns the policy and pays the premiums, and where a claim is admitted the benefit is paid to the employer, which passes it to the employee through payroll. Some providers allow cover to include employer costs such as national insurance and pension contributions, depending on the plan.</p>

<p>As with any income protection, the definition of incapacity, the waiting period, the maximum benefit period and how benefit interacts with other income decide what a claim is worth. All sit in the policy wording and vary between insurers.</p>

<h2>Ownership, benefit and purpose must line up</h2>

<p>Almost every problem found when reviewing existing business protection is a mismatch. A policy owned personally when the loss falls on the company. A trust drafted for family protection used for a shareholding. A key person policy still covering someone who left. An agreement drafted but never signed. They are also not set and forget: a new shareholder, a departure, a refinancing or a change of legal structure can each break the logic of something that was sound when written.</p>

<h2>What an adviser will want to understand</h2>

<ul class="na-checklist">
<li>The legal structure of the business and who owns what, in writing</li>
<li>Who the business could not function without, and for how long</li>
<li>What borrowing exists, on what terms, and whether personal guarantees have been given</li>
<li>Whether a shareholders\', partnership or LLP members\' agreement exists, and what it says on death and illness</li>
<li>How the business has been valued, when, and by whom</li>
<li>Any existing policies and benefits, who owns them, and who would receive the money</li>
<li>Who the accountant and solicitor are, since legal and tax advice is needed alongside the insurance advice</li>
</ul>

<p class="na-disclaimer">This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.</p>
</div></section>');
    }

    public static function guide_when_to_review_protection_insurance(): string
    {
        return self::html('<section class="na-section"><div class="na-shell na-prose">
<nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a> <span aria-hidden="true">/</span> <a href="/guides/">Guides</a> <span aria-hidden="true">/</span> <span>When to review cover</span></nav>

<p class="na-eyebrow">Protection reviews</p>

<p class="na-lede">Protection policies are arranged for a reason, and the reason moves. A review is a check that the amount, the term, the ownership and the purpose of what you hold still match the life you are actually living.</p>

<h2>A review is not the same as a replacement</h2>

<p>Reviewing cover and changing it are separate steps. Existing policies frequently contain terms that cannot be recreated. They were underwritten when you were younger and possibly healthier, and may include options and definitions no longer available on new business. A review can reasonably conclude that everything should stay as it is, that a smaller policy should be added alongside what you have, or that an administrative change such as a trust is the only thing needed. Replacing a policy carries the most risk of the available outcomes.</p>

<h2>Home and mortgage events</h2>

<ul>
<li>Buying a property, or moving to one with a larger mortgage, changes the debt that would need clearing.</li>
<li>Remortgaging with additional borrowing increases the balance even where the monthly payment does not rise much.</li>
<li>Extending the term means the debt outlasts a policy written to the original end date.</li>
<li>Moving between repayment and interest only changes whether a decreasing policy still tracks the balance. Decreasing cover reduces on an assumed rate, so it can fall faster or slower than the actual debt.</li>
<li>Buying with someone else, or taking a name off the mortgage, changes who the cover needs to protect.</li>
<li>A second property or a buy to let introduces borrowing that existing cover was never sized for.</li>
</ul>

<p>Mortgage protection is also where people discover they are covered for the debt and nothing else. Clearing the mortgage removes one cost. It does not replace the income that paid for everything else.</p>

<h2>Family events</h2>

<p>A new or adopted child adds a dependant, usually for a long period, and changes both the amount needed and how long it is needed for. Marriage or civil partnership changes who would inherit, and separation or divorce changes it again, often without anyone updating the paperwork. Other changes matter too: a child who will depend on you indefinitely, an elderly parent you support, or a child finishing education, which can reduce a need rather than increase one.</p>

<p>Separation deserves attention where a joint policy exists, because a joint life policy generally cannot simply be split into two. Some policies include a separation option allowing two single policies to be issued without further medical evidence, but that is a feature the policy either has or does not have, it carries its own conditions and time limits, and it can only be used while the policy is in force.</p>

<h2>Work and income events</h2>

<p>Changing employer resets your benefits, and employer benefits are not portable. Establish what the new arrangements actually are rather than assuming they match the old ones.</p>

<ul>
<li>Group life cover, often called death in service, pays a lump sum to your beneficiaries if you die while employed. It pays on death only and does nothing if you are alive but unable to work.</li>
<li>Group income protection, where an employer provides it, pays during long term incapacity after a deferred period, and has its own incapacity definition which may be less generous than it sounds and may change part way through a claim.</li>
<li>Contractual sick pay varies enormously between employers. Statutory Sick Pay is a legal minimum, and where an employer pays contractual sick pay the statutory element is normally included within it rather than paid afterwards.</li>
<li>Becoming self employed or a company director removes employer sick pay altogether and changes how income is defined for an income protection claim, particularly where remuneration mixes salary and dividends.</li>
<li>A significant change in earnings affects both what is needed and what an insurer will allow you to insure.</li>
<li>Reducing hours, a career break, or a partner returning to work all change the household\'s exposure.</li>
</ul>

<h2>Health events</h2>

<p>Health changes cut both ways. A new diagnosis is a reason to check what you already hold and to be careful about touching it, because replacing cover after a diagnosis is usually harder and sometimes not possible. It is also worth checking whether the condition could give rise to a claim under an existing critical illness policy or trigger a waiver of premium benefit.</p>

<p>Improvements can matter too. Insurers price for smoking status and will usually consider revised terms once someone has been free of nicotine for a stated continuous period, though that normally means a new application rather than an amendment. A resolved condition or a period of stability after treatment can also change the terms an insurer would offer. None of this is guaranteed, and any outcome depends on the insurer\'s assessment of your circumstances.</p>

<h2>Policy events with their own deadlines</h2>

<div class="na-callout-grid">
<div class="na-callout"><h3>Guaranteed insurability options</h3><p>Where a policy includes them, these allow cover to be increased on specified events such as a birth, a marriage or an increased mortgage, without new medical evidence. They are subject to limits, age caps and a window after the event, and can only be used while the policy is in force.</p></div>
<div class="na-callout"><h3>Indexation</h3><p>Index linked policies increase cover and premiums each year in line with a stated measure. Declining increases keeps the premium down but lets the real value erode, and repeated refusals can end the facility on some policies.</p></div>
<div class="na-callout"><h3>Reviewable premiums</h3><p>Some policies have premiums reviewed at set intervals rather than guaranteed for the term. A review can change the cost significantly, and is a natural point to look at the whole arrangement.</p></div>
<div class="na-callout"><h3>A term approaching its end</h3><p>Cover ends on the end date and pays nothing afterwards. If the need continues beyond it, address that well in advance, while you are younger and the options are wider.</p></div>
</div>

<h2>Why cancelling before replacement cover is in force is dangerous</h2>

<p>This is where reviews cause real harm, and the sequence is what protects you. New cover must be applied for, accepted, issued and confirmed as on risk before anything existing is cancelled. Not applied for, not provisionally accepted. On risk, in writing. The reasons are cumulative:</p>

<ul>
<li>You are older than when the original policy was written, and age is a primary pricing factor.</li>
<li>Your health may have changed. Anything since the original application is disclosable and may lead to exclusions, higher terms, postponement or a decline.</li>
<li>New policies restart their own clocks. Critical illness policies apply a survival period after diagnosis, and some benefits, particularly children\'s cover and total permanent disability, apply an initial qualifying period, income protection restarts its deferred period, and life policies commonly restart a suicide or self inflicted injury exclusion.</li>
<li>Definitions differ between insurers and product generations. A condition covered under an older critical illness policy may be defined more tightly, or not listed at all, under a new one.</li>
<li>A gap of even a single day is a period with no cover, and events do not schedule themselves around administration.</li>
</ul>

<p>Cancelling a direct debit is not neutral either. Missed premiums can put a policy into a lapse process, and reinstating it afterwards is at the insurer\'s discretion and may require fresh medical information.</p>

<h2>What can usually be changed, and what usually cannot</h2>

<p>Insurers differ and the policy conditions govern, but the pattern is consistent. Changes often made without new underwriting include reducing the sum assured, reducing the term, removing an optional benefit, changing the payment date or bank details, placing the policy into trust, and appointing or removing trustees within the powers the deed gives. Whether beneficiaries can be changed depends on the type of trust: under a discretionary or flexible trust the trustees can usually exercise discretion within the class, but under a bare or absolute trust the beneficiaries are fixed. Where a policy is held on trust the trustees are the legal owners and must be involved in any change. Reductions are straightforward because they reduce the insurer\'s risk, though they are rarely reversible on the same terms.</p>

<p>Changes that generally require new underwriting, and often a new policy altogether, include increasing the sum assured, extending the term, adding critical illness cover to a life policy, adding waiver of premium, shortening a deferred period, and converting decreasing cover to level cover. Some insurers will consider alterations subject to evidence of health, many will simply issue a new contract. Where a guaranteed insurability option exists and the event qualifies, an increase may be possible without medical evidence, but only within the option\'s rules and only while the policy remains in force.</p>

<h2>Ownership, trusts and beneficiaries</h2>

<p>A review should look at who would receive the money as closely as at how much there is. Life cover written in trust generally pays to trustees rather than through the estate, which can mean payment without waiting for probate and can keep the proceeds outside the estate for inheritance tax purposes. Trusts have consequences and are not automatically right for every policy, and the tax rules change: from 6 April 2025 the scope of UK inheritance tax on an individual\'s worldwide assets is determined by a long term residence test rather than by domicile. How this applies to you depends on your circumstances, and specialist tax or legal advice may be needed.</p>

<p>Beneficiary details also go stale. Nominations made before a divorce, a remarriage or a bereavement are a common and avoidable problem, as are trusts where a named trustee has died. Employer death in service nominations sit outside your personal policies and need updating separately.</p>

<h2>What an adviser will want to understand</h2>

<ul class="na-checklist">
<li>Every policy currently in force, with schedules, including any arranged years ago and forgotten.</li>
<li>Which policies are in trust, who the trustees are, and who the beneficiaries currently are.</li>
<li>Your current mortgage balance, term, repayment basis and any additional borrowing.</li>
<li>Who depends on you financially, and for how long that is likely to continue.</li>
<li>What your employer provides, including sick pay terms and any group scheme rules.</li>
<li>Changes to your health since your existing policies were arranged.</li>
<li>Your smoking status and whether it has changed.</li>
<li>Whether any policy has options, index linking or a premium review date coming up.</li>
<li>What has changed since the cover was last looked at, including anything you think is minor.</li>
<li>What you can sustain as an ongoing premium, so that cover survives long enough to be useful.</li>
</ul>

<p class="na-disclaimer">This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.</p>
</div></section>');
    }
}
