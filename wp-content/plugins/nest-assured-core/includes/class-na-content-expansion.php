<?php
/**
 * Long-form editorial content added to the Nest Assured guide library.
 *
 * @package NestAssuredCore
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

final class NA_Content_Expansion
{
    public static function editorial_policy(): string
    {
        return self::html('
<section class="na-section"><div class="na-shell na-prose">
  <p class="na-eyebrow">How we publish</p><p class="na-lede">Nest Assured guides are designed to make an adviser conversation easier to understand. They provide general education, not a quote or personal recommendation.</p>
  <h2>Who writes and reviews the guides</h2><p>The Nest Assured editorial team prepares guide content using established product concepts, policy terminology and official consumer information. Ollie Allen, Protection Adviser, reviews the material for practical clarity and alignment with the advice journey.</p>
  <h2>Sources we prioritise</h2><ul class="na-checklist"><li>Financial Conduct Authority consumer information and applicable regulatory principles</li><li>UK Government and HMRC guidance where legal or tax context is mentioned</li><li>MoneyHelper consumer education</li><li>Association of British Insurers consumer resources</li><li>Insurer policy documents when explaining product-specific wording</li></ul>
  <h2>How we keep content clear</h2><p>We distinguish general explanations from personal advice, avoid promises about eligibility or claims, describe important trade-offs and direct readers back to policy wording where definitions vary. Medical information is never requested through editorial tools or the public enquiry form.</p>
  <h2>Review and correction process</h2><p>Each guide shows its most recent review date. We review content when products, regulation, official guidance or the Nest Assured advice journey materially change. If you believe a guide is unclear or inaccurate, use the <a href="/contact/">contact route</a> and identify the page and wording concerned. The team will assess the point and record any material correction through the page review date.</p>
  <h2>Commercial independence</h2><p>Guide placement is not sold to insurers and the library does not rank providers. A recommendation, if appropriate, is made only after an adviser has considered the client’s circumstances, existing arrangements, affordability and relevant policy terms.</p>
  <div class="na-cta"><div><h2>Need guidance about your own circumstances?</h2><p>A guide can frame the questions. An adviser conversation is where individual needs and options can be considered.</p></div><a class="na-button na-button--light" href="/enquire/">Talk to an adviser</a></div>
</div></section>');
    }

    /**
     * @return array<int, array{0:string, 1:string, 2:string}>
     */
    public static function guides(): array
    {
        return [
            ['Making a protection insurance claim', 'making-a-protection-insurance-claim', self::claims_support()],
            ['Insurance jargon buster', 'insurance-jargon-buster', self::jargon_buster()],
            ['Income protection for self-employed people', 'income-protection-for-self-employed', self::self_employed_income()],
            ['Relevant life cover or key person cover?', 'relevant-life-vs-key-person-cover', self::relevant_life_vs_key_person()],
            ['Leaving a company private medical scheme', 'leaving-company-private-medical-insurance', self::leaving_company_pmi()],
            ['Life insurance and trusts', 'life-insurance-and-trusts', self::life_insurance_and_trusts()],
            ['Preparing for a protection appointment', 'preparing-for-protection-appointment', self::appointment_checklist()],
        ];
    }

    private static function html(string $content): string
    {
        return "<!-- wp:html -->\n" . trim($content) . "\n<!-- /wp:html -->";
    }

    private static function claims_support(): string
    {
        return self::html('
<article class="na-section"><div class="na-shell na-prose na-guide-article">
  <nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a><span aria-hidden="true">/</span><a href="/guides/">Guides</a><span aria-hidden="true">/</span><span>Making a protection insurance claim</span></nav>
  <p class="na-eyebrow">Claims and policy support</p><p class="na-lede">A claim can arrive at a difficult time. Knowing where the policy is, who to contact and which information may be requested can make the first steps feel more manageable.</p>
  <h2>Start with the policy provider</h2><p>The insurer is responsible for assessing the claim. Its claims team can explain the form, evidence and next steps that apply to the policy. The provider name and policy number will normally appear on the policy schedule, annual statement or direct-debit reference.</p>
  <h2>Information commonly requested</h2><ul class="na-checklist"><li>The policy number and contact details for the policyholder</li><li>The reason for the claim and the relevant date</li><li>Medical, employment or financial evidence appropriate to the cover</li><li>Details of trustees, beneficiaries or legal representatives where relevant</li><li>Permission for the insurer to obtain information from appropriate third parties</li></ul>
  <h2>Different cover means different evidence</h2><div class="na-callout-grid"><div class="na-callout"><h3>Life insurance</h3><p>The insurer may need a death certificate, policy details and information about ownership or any trust.</p></div><div class="na-callout"><h3>Critical illness cover</h3><p>Medical evidence is used to assess whether the diagnosis meets the policy definition.</p></div><div class="na-callout"><h3>Income protection</h3><p>Evidence may cover incapacity, occupation, earnings and continuing eligibility during the claim.</p></div><div class="na-callout"><h3>Private medical insurance</h3><p>Authorisation is often needed before eligible private treatment begins, except where the policy states otherwise.</p></div></div>
  <h2>Keep a simple record</h2><p>Note the date, the person you spoke with, any claim reference and the information requested. Keep copies of forms and supporting documents. Ask the insurer how updates will be provided and whether anything has a deadline.</p>
  <h2>If you cannot find the details</h2><p>Check bank statements, email records and policy files first. An adviser may be able to help identify a policy arranged through their firm, but only the insurer can assess and decide a claim. Do not send medical records through the website enquiry form.</p>
  <p class="na-disclaimer">This guide is general information. Claim requirements and decisions depend on the policy terms and the insurer’s assessment.</p>
  <div class="na-cta"><div><h2>Need help finding the right route?</h2><p>Tell us which policy or conversation you are trying to reconnect with, without including medical information.</p></div><a class="na-button na-button--light" href="/contact/">Choose a contact route</a></div>
</div></article>');
    }

    private static function jargon_buster(): string
    {
        return self::html('
<article class="na-section"><div class="na-shell na-prose na-guide-article">
  <nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a><span aria-hidden="true">/</span><a href="/guides/">Guides</a><span aria-hidden="true">/</span><span>Insurance jargon buster</span></nav>
  <p class="na-eyebrow">Plain-English glossary</p><p class="na-lede">Insurance language should help you compare cover, not make the decision harder. These short definitions explain common terms, but the policy wording remains the final reference.</p>
  <dl class="na-glossary">
    <div><dt>Benefit</dt><dd>The money or service provided when a valid claim meets the policy terms.</dd></div>
    <div><dt>Decreasing cover</dt><dd>Cover designed to reduce over time, often considered alongside a repayment mortgage.</dd></div>
    <div><dt>Deferred or waiting period</dt><dd>The period an eligible income protection claimant must wait before benefit can begin.</dd></div>
    <div><dt>Excess</dt><dd>The amount a policyholder contributes towards an eligible claim, commonly used in private medical and home insurance.</dd></div>
    <div><dt>Exclusion</dt><dd>A circumstance, condition or event the policy does not cover.</dd></div>
    <div><dt>Full medical underwriting</dt><dd>An underwriting approach where health information is assessed when cover is applied for.</dd></div>
    <div><dt>Guaranteed premium</dt><dd>A premium basis where the insurer cannot change the price because of claims or general review, although agreed indexation may still alter it.</dd></div>
    <div><dt>Incapacity definition</dt><dd>The policy test used to assess whether illness or injury prevents the insured person from working.</dd></div>
    <div><dt>Indexation</dt><dd>An option designed to increase cover over time, normally with corresponding premium changes.</dd></div>
    <div><dt>Moratorium underwriting</dt><dd>A private medical underwriting approach that initially excludes certain recent conditions, with future eligibility determined by the policy rules.</dd></div>
    <div><dt>Policy term</dt><dd>The period during which cover is intended to run, provided required premiums are maintained.</dd></div>
    <div><dt>Reviewable premium</dt><dd>A premium the insurer may review under the conditions described in the policy.</dd></div>
    <div><dt>Sum assured</dt><dd>The amount of cover selected for a lump-sum protection policy.</dd></div>
    <div><dt>Trust</dt><dd>A legal arrangement in which trustees hold and manage policy benefits for the intended beneficiaries.</dd></div>
    <div><dt>Underwriting</dt><dd>The insurer’s process for deciding whether it can offer cover and on what terms.</dd></div>
  </dl>
  <p class="na-disclaimer">Definitions are simplified for general education. Always check the insurer’s policy wording and personalised documents.</p>
  <div class="na-cta"><div><h2>Want a term explained in context?</h2><p>An adviser can connect the wording to the cover and choices you are considering.</p></div><a class="na-button na-button--light" href="/enquire/">Talk to an adviser</a></div>
</div></article>');
    }

    private static function self_employed_income(): string
    {
        return self::html('
<article class="na-section"><div class="na-shell na-prose na-guide-article">
  <nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a><span aria-hidden="true">/</span><a href="/guides/">Guides</a><span aria-hidden="true">/</span><span>Income protection for self-employed people</span></nav>
  <p class="na-eyebrow">Self-employed income protection</p><p class="na-lede">When there is no employer sick-pay scheme, the gap between stopping work and receiving financial support can be especially important to understand.</p>
  <h2>Start with the real monthly gap</h2><p>List essential household spending, regular business commitments, accessible savings and any benefits or other household income. The aim is to understand how long existing resources might last, not automatically to replace every pound earned.</p>
  <h2>How earnings may be evidenced</h2><p>Insurers can ask for accounts, tax calculations, tax-year overviews, bank statements or accountant confirmation. The evidence required and the definition of earnings vary, particularly for directors who receive a mixture of salary and dividends.</p>
  <h2>Four details to compare</h2><div class="na-feature-list"><div><span>01</span><h3>Occupation definition</h3><p>Understand how the policy tests whether you are unable to carry out your work.</p></div><div><span>02</span><h3>Waiting period</h3><p>Match the delay before benefit to savings and other support you could use first.</p></div><div><span>03</span><h3>Benefit calculation</h3><p>Check how the insurer defines and verifies eligible earnings at application and claim.</p></div><div><span>04</span><h3>Claim support</h3><p>Rehabilitation and return-to-work services can matter alongside the financial benefit.</p></div></div>
  <h2>Sole trader, partnership or limited company?</h2><p>The legal and income structure can change which personal or employer-owned arrangements are worth discussing. Executive income protection may be relevant to some limited companies, while a personal policy may fit a different setup. Tax treatment is outside the scope of this guide and should be checked with an appropriate professional.</p>
  <h2>What to bring to a conversation</h2><ul class="na-checklist"><li>Your role and the physical or technical duties involved</li><li>Recent accounts or tax documents</li><li>Regular drawings, salary, dividends or partnership income</li><li>Household and business costs that would continue</li><li>Savings and existing personal or business cover</li></ul>
  <p class="na-disclaimer">This guide is general information, not personal, tax or accounting advice. Eligibility and benefit calculations depend on insurer terms and evidence.</p>
  <div class="na-cta"><div><h2>Map the gap before comparing cover</h2><p>Bring your income evidence, essential outgoings and a realistic view of how long savings could help.</p></div><a class="na-button na-button--light" href="/enquire/?topic=income-protection">Talk to an adviser</a></div>
</div></article>');
    }

    private static function relevant_life_vs_key_person(): string
    {
        return self::html('
<article class="na-section"><div class="na-shell na-prose na-guide-article">
  <nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a><span aria-hidden="true">/</span><a href="/guides/">Guides</a><span aria-hidden="true">/</span><span>Relevant life or key person cover</span></nav>
  <p class="na-eyebrow">Business protection comparison</p><p class="na-lede">Relevant life and key person cover can both involve a company paying premiums, but they protect different people from different financial problems.</p>
  <div class="na-comparison" role="region" aria-label="Relevant life and key person comparison" tabindex="0"><div class="na-comparison__head"><span>Question</span><span>Relevant life cover</span><span>Key person cover</span></div><div><strong>Who is the intended protection for?</strong><span data-label="Relevant life cover">The eligible employee’s beneficiaries</span><span data-label="Key person cover">The business</span></div><div><strong>What problem is it designed around?</strong><span data-label="Relevant life cover">An employee death-in-service benefit</span><span data-label="Key person cover">Financial disruption following the loss of an important person</span></div><div><strong>Who may receive the policy benefit?</strong><span data-label="Relevant life cover">Trustees for the beneficiaries</span><span data-label="Key person cover">The business, subject to the arrangement</span></div><div><strong>How is the amount considered?</strong><span data-label="Relevant life cover">Employee circumstances and benefit purpose</span><span data-label="Key person cover">Business contribution, replacement costs, debt or profit exposure</span></div></div>
  <h2>Relevant life cover</h2><p>A relevant life policy is generally considered as an employer-provided death-in-service benefit for an eligible employee or director. It is normally written under an appropriate trust. Eligibility, taxation and trust requirements need professional consideration.</p>
  <h2>Key person cover</h2><p>Key person cover is designed around the financial effect that death or covered illness of an important person could have on the business. The business identifies and documents the risk, owns the policy in a typical arrangement and may receive the benefit.</p>
  <h2>Questions that prevent confusion</h2><ul class="na-checklist"><li>Whose financial loss is the arrangement intended to address?</li><li>Who should own the policy and receive any benefit?</li><li>How has the proposed amount been calculated?</li><li>What legal, trust, accounting or tax advice is also required?</li><li>When will the arrangement and valuation be reviewed?</li></ul>
  <p class="na-disclaimer">This guide is general information and not tax, legal or accounting advice. Business structure, ownership and current rules affect the appropriate arrangement.</p>
  <div class="na-cta"><div><h2>Start with the business risk</h2><p>Clarify who needs protecting and what financial outcome the arrangement is meant to support.</p></div><a class="na-button na-button--light" href="/enquire/?topic=business-protection">Talk to an adviser</a></div>
</div></article>');
    }

    private static function leaving_company_pmi(): string
    {
        return self::html('
<article class="na-section"><div class="na-shell na-prose na-guide-article">
  <nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a><span aria-hidden="true">/</span><a href="/guides/">Guides</a><span aria-hidden="true">/</span><span>Leaving a company private medical scheme</span></nav>
  <p class="na-eyebrow">Private medical insurance transition</p><p class="na-lede">Changing jobs, retiring or losing a company benefit can create a short window in which to understand continuation options and avoid an unintended gap in private medical cover.</p>
  <h2>Confirm the end date first</h2><p>Ask the employer or scheme administrator when cover ends and whether any treatment already under way remains eligible. Do not assume it continues to the end of the month or until a replacement policy starts.</p>
  <h2>Ask about continuation terms</h2><p>Some providers may offer a route from a company scheme to an individual policy. The deadline, medical underwriting and treatment of existing conditions vary. A continuation option is not automatically the best-value choice, but it can be important to understand before the opportunity expires.</p>
  <h2>Compare more than the premium</h2><ul class="na-checklist"><li>How existing and recent conditions will be treated</li><li>Hospital list and guided-care requirements</li><li>Outpatient, diagnostic, cancer and mental-health benefits</li><li>Excess, benefit limits and optional cover</li><li>How claims and age may affect renewal pricing</li><li>Whether partners or children also need new arrangements</li></ul>
  <h2>If treatment is already planned</h2><p>Contact the existing insurer before making changes. Ask how authorisation, ongoing treatment and any transfer to a new policy would be handled. A new insurer may not cover treatment that began before its policy started.</p>
  <h2>Information to gather</h2><p>Keep the current membership certificate, policy or scheme booklet, renewal information, claims authorisations and the leaving date. An adviser can use those details to compare the current benefits with available options.</p>
  <p class="na-disclaimer">This guide is general information. Continuation rights, underwriting and treatment eligibility depend on the company scheme and insurer terms.</p>
  <div class="na-cta"><div><h2>Review your options before company cover ends</h2><p>Bring the scheme documents, end date and the benefits you most want to preserve.</p></div><a class="na-button na-button--light" href="/enquire/?topic=private-medical-insurance">Talk to an adviser</a></div>
</div></article>');
    }

    private static function life_insurance_and_trusts(): string
    {
        return self::html('
<article class="na-section"><div class="na-shell na-prose na-guide-article">
  <nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a><span aria-hidden="true">/</span><a href="/guides/">Guides</a><span aria-hidden="true">/</span><span>Life insurance and trusts</span></nav>
  <p class="na-eyebrow">Policy ownership and beneficiaries</p><p class="na-lede">A trust can help define who should manage and receive a life-insurance benefit. It is a legal arrangement, so the purpose and wording need careful consideration.</p>
  <h2>What a trust does</h2><p>A trust separates legal control of the policy from the people intended to benefit. The trustees manage the policy and any proceeds in line with the trust terms for the beneficiaries.</p>
  <h2>Why it may be discussed</h2><div class="na-callout-grid"><div class="na-callout"><h3>Clarity</h3><p>The arrangement can identify the intended beneficiaries and the people responsible for administering the benefit.</p></div><div class="na-callout"><h3>Administration</h3><p>Where a valid claim is payable to trustees, the insurer may be able to pay without waiting for the policy to pass through the deceased person’s estate.</p></div><div class="na-callout"><h3>Control</h3><p>Trustees can manage money for children or other beneficiaries under the trust terms.</p></div><div class="na-callout"><h3>Estate planning</h3><p>A trust may affect how proceeds are treated, but tax outcomes depend on circumstances and current rules.</p></div></div>
  <h2>Decisions that need attention</h2><ul class="na-checklist"><li>Which trust form matches the intended purpose</li><li>Who should be appointed as trustees</li><li>Who should benefit and whether flexibility is needed</li><li>How trustees will keep contact details and documents current</li><li>Whether legal or tax advice is required</li></ul>
  <h2>Do not treat the form as an afterthought</h2><p>Trust wording can be difficult to reverse, and changes in relationships or family circumstances may affect the intended outcome. Keep copies with the policy documents and make sure trustees know about the arrangement.</p>
  <p class="na-disclaimer">This guide is general information and not legal or tax advice. Trust suitability and tax treatment depend on personal circumstances and current rules.</p>
  <div class="na-cta"><div><h2>Discuss the purpose before choosing an arrangement</h2><p>Start with who the benefit is intended to help and who should be responsible for it.</p></div><a class="na-button na-button--light" href="/enquire/?topic=life-insurance">Talk to an adviser</a></div>
</div></article>');
    }

    private static function appointment_checklist(): string
    {
        return self::html('
<article class="na-section"><div class="na-shell na-prose na-guide-article">
  <nav class="na-breadcrumbs" aria-label="Breadcrumb"><a href="/">Home</a><span aria-hidden="true">/</span><a href="/guides/">Guides</a><span aria-hidden="true">/</span><span>Preparing for a protection appointment</span></nav>
  <p class="na-eyebrow">Adviser conversation checklist</p><p class="na-lede">A little preparation helps an adviser spend less time gathering basics and more time explaining the choices that matter.</p>
  <h2>Your existing safety net</h2><ul class="na-checklist"><li>Personal policy schedules and trust documents</li><li>Employer sick pay, death-in-service and medical benefits</li><li>Savings that could support essential costs</li><li>Any cover held by a partner or through a business</li></ul>
  <h2>Your commitments</h2><ul class="na-checklist"><li>Mortgage balance, type and remaining term</li><li>Loans and other debts</li><li>Essential monthly household spending</li><li>People who rely on your income, care or other contribution</li><li>Business borrowing, ownership or personal guarantees where relevant</li></ul>
  <h2>Your work and income</h2><p>Bring a recent payslip or suitable self-employed income evidence, plus information about occupation, working pattern and benefits. Sensitive medical information should be discussed privately with the adviser, not entered into the website form.</p>
  <h2>Questions worth asking</h2><div class="na-callout-grid"><div class="na-callout"><h3>Purpose</h3><p>What specific financial risk is this recommendation intended to address?</p></div><div class="na-callout"><h3>Trade-offs</h3><p>Which choices change the cost, scope or likelihood of the cover remaining affordable?</p></div><div class="na-callout"><h3>Definitions</h3><p>Which policy terms, exclusions or claim definitions deserve particular attention?</p></div><div class="na-callout"><h3>Review</h3><p>What changes should prompt another look, and what should happen before replacing existing cover?</p></div></div>
  <h2>You do not need every answer in advance</h2><p>The adviser’s role is to help organise the information, explain uncertainty and identify gaps. If a document is missing, make a note of it rather than guessing.</p>
  <p class="na-disclaimer">This checklist is general information. The evidence needed depends on the cover and your circumstances.</p>
  <div class="na-cta"><div><h2>Ready to start the conversation?</h2><p>Choose the right route and share only the basic information needed to connect you with an adviser.</p></div><a class="na-button na-button--light" href="/enquire/">Talk to an adviser</a></div>
</div></article>');
    }
}
