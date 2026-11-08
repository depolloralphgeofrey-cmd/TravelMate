<?php
$pageTitle = 'References';
$currentPage = 'references';
include 'includes/header.php';

$refs = [
  'Alshaye, I., Tasir, Z., & Jumaat, N. F. (2020). The conceptual framework of online problem-based learning towards problem-solving ability and programming skills. 2019 International Conference on Electrical Engineering and Informatics (ICEEI).',
  'Birenboim, A., Bulis, Y., & Omer, I. (2023). A typology of tourism mobility apps. Tourism Management Perspectives, 48, 101161. https://doi.org/10.1016/j.tmp.2023.101161',
  'Board of Investments. (2025). Doing business in the Philippines 2025. https://www.eccp.com/storage/app/media/doing-business/Doing%20Business%20in%20the%20Philippines%202025.pdf',
  'Bureau of Fire Protection (BFP). (2026). Fire Safety Inspection Certificate (FSIC) and fire safety requirements. https://bfp.gov.ph/',
  'Bureau of Internal Revenue (BIR). (2026). Registration. https://www.bir.gov.ph/',
  'Chin, J. T. (2020). Location choice of new business establishments: Understanding the local context and neighborhood conditions in the United States. Sustainability, 12(2), Article 501.',
  'De Castro, A. N., De Gucena, M. G., Serrano, M. G. A., & Etrata, A. J. (2025). Factors affecting travel intention and purchase behavior of airline passengers: The mediating role of promotional discounts in airline tickets. International Journal of Research in Business and Social Science, 14(3), 1–17. https://doi.org/10.20525/ijrbs.v14i3.4096',
  'Department of Trade and Industry (DTI). (2026). Business name registration and business regulations. https://www.dti.gov.ph/',
  'Department of Tourism (DOT). (2026). Accreditation. https://tourism.gov.ph/',
  'Fan, L., Chatterjee, S., & Kim, J. (2021). An Integrated Framework of Young Adults’ Subjective Well-Being: The Roles of Personality Traits, Financial Responsibility, Perceived Financial Capability, and Race. Journal of Family and Economic Issues, 43, 1-20. 10.1007/s10834-021-09764-6',
  'Hamouda, M. (2022). Mobile Applications in Tourism: Examining the Determinants of Intention to Use. International Journal of Technology and Human Interaction (IJTHI), 18(1), 1-13. https://doi.org/10.4018/IJTHI.293198',
  'Kim, J., et al. (2026). The psychology of price discount depth: How price reductions shape vacation decision-making. Journal of Travel Research. https://doi.org/10.1177/13567667261473698',
  'Kuo, T.-S., Huang, K., Thang, N., & Phuc Hung, N. (2019). Adoption of mobile applications for identifying tourism destinations by travellers: An integrative approach. Journal of Business Economics and Management, 20, 860-877. 10.3846/jbem.2019.10448',
  'Lam, X. Y., & Ramasamy, R. K. (2026). A safety-enhanced and trust-aware recommendation framework for travel companion matching. Information, 17(5), 406. https://doi.org/10.3390/info17050406',
  'Madrazo, D. (2026). DOLE: NCR minimum wage to rise by P60. The Manila Times. https://www.manilatimes.net/2026/09/13/news/national/dole-ncr-minimum-wage-to-rise-by-p60/2423884',
  'Makati City Government. (2026). Business Permits and Licensing Office. https://www.makati.gov.ph/',
  'Mechan, F. (2019). Payroll Policy. The University of Edinburgh. https://edwebcontent.ed.ac.uk/sites/default/files/atoms/files/payroll_policyv1.1.pdf',
  'Muwada, K., Richard, K., & Shamirah, B. (2026). Business partnerships and strategic alliances on the performance of Coca-Cola Beverages Uganda Limited. 5, 329-338.',
  'National Privacy Commission. (2025). NPC Advisory No. 2025-02: Guidelines on privacy engineering in systems life cycle processes. https://privacy.gov.ph/wp-content/uploads/2025/12/NPC_Advisory2025-02.pdf',
  'OWASP Foundation. (2024). MASVS v2.1.0 release & MASVS-PRIVACY. https://mas.owasp.org/news/2024/01/18/masvs-v210-release--masvs-privacy/',
  'Pascua, D. S. (2020). Sustainable corporate social responsibility practices of business establishments in Region 2, Philippines. Journal of Critical Reviews, 7(11), 53–59. https://doi.org/10.31838/jcr.07.11.08',
  'Salazar, M., Chico, M., Solarte, A., & Villagómez, E. (2026). Determination of discount rates in business valuation and public project evaluation: A systematic review from the perspective of international finance and financial theory. Multidisciplinary Reviews, 10, 2027091. 10.31893/multirev.2027091',
  'Securities and Exchange Commission (SEC). (2026). Company registration. https://www.sec.gov.ph/',
  'World Health Organization. (2020). Cleaning and disinfection of environmental surfaces in the context of COVID-19. https://iris.who.int/server/api/core/bitstreams/71dad3a2-4ba4-4039-bfb6-33237ccb38de/content',
  'Yasmin, A. (2026). Importance of understanding the marketing mix for business success. International Journal of Science and Research Archive, 20, 098-106. 10.30574/ijsra.2026.20.3.1686',
];
?>
<section class="page-hero">
  <div class="container">
    <div class="crumbs"><a href="home.php">Home</a> / References</div>
    <h1>References</h1>
    <p>Sources cited in the TravelMate business plan.</p>
  </div>
</section>
<section>
  <div class="container prose">
    <?php foreach ($refs as $r): ?>
      <p style="padding-left:2em;text-indent:-2em"><?= htmlspecialchars($r) ?></p>
    <?php endforeach; ?>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
