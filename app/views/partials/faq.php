<section class="faq-section">
  <div class="section-header">
    <h2>Common <span class="highlight">Questions</span></h2>
    <p>Quick answers to things you might wonder about</p>
  </div>
  <div class="entry-content">
    <div class="faq" draggable="false">
      <ul class="faq-accordion">
        <?php
        $stmt = $pdo->prepare("SELECT * FROM faqs ORDER BY display_order ASC LIMIT 10");
        $stmt->execute();
        $faqs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($faqs as $faq): ?>
          <li>
            <input type="checkbox" id="faq-<?= $faq['id'] ?>" class="faq-checkbox">
            <label for="faq-<?= $faq['id'] ?>" class="faq-question"><?= htmlspecialchars($faq['question']) ?></label>
            <div class="faq-answer"><?= nl2br(htmlspecialchars($faq['answer'])) ?></div>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</section>

<style>
  /* FAQ Section */
  .faq-section {
    background-color: var(--primary);
  }

  .faq-accordion {
    list-style: none;
  }

  .faq-accordion li {
    margin-bottom: 1rem;
    border-bottom: 1px solid var(--borderColor);
    position: relative;
  }

  .faq-checkbox {
    position: absolute;
    opacity: 0;
    height: 0;
    width: 0;
  }

  .faq-question {
    font-family: "defBoldFont", sans-serif;
    font-size: 1.2rem;
    padding: 1.5rem 0;
    cursor: pointer;
    position: relative;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 0;
  }

  .faq-question::after {
    content: "+";
    font-size: 1.5rem;
    color: var(--accentBlue);
    transition: var(--transition);
  }

  .faq-checkbox:checked+.faq-question::after {
    content: "-";
  }

  .faq-answer {
    max-height: 0;
    text-wrap: initial;
    overflow: hidden;
    transition: max-height 0.3s ease;
    color: var(--textSecondary);
    line-height: 1.6;
    margin: 0;
    padding: 0;
  }

  .faq-checkbox:checked+.faq-question+.faq-answer {
    max-height: 500px;
    padding-bottom: 1.5rem;
  }
</style>