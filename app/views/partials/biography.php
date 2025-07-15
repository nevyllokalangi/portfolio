<section class="biography-section">
  <header class="biography-header">
    <h1>My Journey So Far</h1>
  </header>
  <?php
  // Get all biography entries ordered by year
  $stmt = $pdo->prepare("SELECT * FROM biography ORDER BY year ASC");
  $stmt->execute();
  $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Group entries by year
  $groupedEntries = [];
  foreach ($entries as $entry) {
    $groupedEntries[$entry['year']][] = $entry;
  }
  ?>

  <article class="biography-timeline">
    <nav class="biography-timeline__nav">
      <ul>
        <?php foreach ($groupedEntries as $year => $items): ?>
          <li data-year="<?= htmlspecialchars($year) ?>">
            <span><?= htmlspecialchars($year) ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <section class="biography-timeline__section">
      <div class="biography-wrapper">
        <?php foreach ($groupedEntries as $year => $items): ?>
          <div class="biography-milestone" id="year-<?= htmlspecialchars($year) ?>">
            <h2><?= htmlspecialchars($year) ?></h2>
            <?php foreach ($items as $item): ?>
              <div class="biography-content">
                <h3 class="biography-heading"><?= htmlspecialchars($item['heading']) ?></h3>
                <p class="biography-desc"><?= nl2br(htmlspecialchars($item['description'])) ?></p>
                <?php if (!empty($item['image_path'])): ?>
                  <img class="biography-img" src="<?= htmlspecialchars($item['image_path']) ?>"
                    alt="<?= htmlspecialchars($item['heading']) ?>" loading="lazy" />
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  </article>
</section>

<style>
  /* Biography Timeline */
  .biography-section {
    padding: 100px 0;
  }

  .biography-timeline {
    position: relative;
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
  }

  .biography-timeline__nav {
    position: sticky;
    align-self: flex-start;
    top: 100px;
    width: 200px;
    flex-shrink: 0;
    margin-right: 50px;
    height: calc(100vh - 200px);
    overflow: hidden;
    padding: 20px 0;
  }

  .biography-timeline__nav ul {
    list-style: none;
    overflow: hidden;
    padding: 0;
    margin: 0;
  }

  .biography-timeline__nav li {
    padding: 8px;
    cursor: pointer;
    color: var(--textSecondary);
    border-bottom: 1px dotted rgba(0, 0, 0, 0.3);
    transition: all 0.3s ease-out;
    position: relative;
  }

  .biography-timeline__nav li.active {
    font-weight: bold;
    color: var(--accentBlue);
    border-bottom-color: transparent;
    transform: scale(1.05);
  }


  .biography-timeline__section {
    flex-grow: 1;
  }

  .biography-milestone {
    margin-bottom: 60px;
    scroll-margin-top: 120px;
  }

  .biography-milestone h2 {
    font-size: 24px;
    margin-bottom: 20px;
    color: var(--accentBlue);
  }

  .biography-content {
    margin-bottom: 30px;
  }

  .biography-heading {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 10px;
    color: var(--primaryFont);
  }

  .biography-desc {
    text-wrap: initial;
    font-size: 16px;
    line-height: 1.6;
    margin-bottom: 15px;
    color: var(--textSecondary);
  }

  .biography-img {
    max-width: 100%;
    height: auto;
    margin-top: 15px;
    border-radius: 4px;
  }

  @media (max-width: 768px) {
    .biography-timeline {
      flex-direction: column;
    }

    .biography-timeline__nav {
      position: relative;
      top: 0;
      width: 100%;
      height: auto;
      margin-right: 0;
      margin-bottom: 30px;
      padding: 10px 0;
      border-bottom: 1px solid #eee;
    }

    .biography-timeline__nav ul {
      display: flex;
      overflow-x: auto;
      white-space: nowrap;
      padding-bottom: 10px;
    }

    .biography-timeline__nav li {
      display: inline-block;
      margin-right: 20px;
      padding: 0 0 5px 0;
      border-bottom: 2px solid transparent;
    }

    .biography-timeline__nav li.active {
      border-bottom: 2px solid #f94125;
      transform: none;
    }

    .biography-timeline__section {
      padding-top: 0;
    }
  }

  .biography-header {
    text-align: center;
    margin-top: 40px;
    margin-bottom: 40px;
    position: relative;
  }

  .biography-header h1 {
    font-size: 2.8rem;
    font-weight: 800;
    letter-spacing: 1px;
    margin: 0;
    padding-bottom: 12px;
    position: relative;
    display: inline-block;
    background: var(--tertiaryFont);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const timelineNav = document.querySelector('.biography-timeline__nav');
    const milestones = document.querySelectorAll('.biography-milestone');
    const navItems = document.querySelectorAll('.biography-timeline__nav li');

    // Calculate the correct offset by getting the nav's sticky position
    const navStickyTop = parseInt(getComputedStyle(timelineNav).top) || 100;
    const timelineSectionTop = document.querySelector('.biography-timeline__section').offsetTop;

    function highlightCurrentSection() {
      let currentActiveIndex = -1;
      const viewportMiddle = window.scrollY + (window.innerHeight / 3);

      milestones.forEach((milestone, index) => {
        const rect = milestone.getBoundingClientRect();
        const milestoneTop = rect.top + window.scrollY;
        const milestoneBottom = milestoneTop + rect.height;

        if (viewportMiddle >= milestoneTop && viewportMiddle <= milestoneBottom) {
          currentActiveIndex = index;
        }
      });

      navItems.forEach(item => item.classList.remove('active'));

      if (currentActiveIndex >= 0) {
        navItems[currentActiveIndex].classList.add('active');

        if (window.innerWidth < 768) {
          const activeItem = navItems[currentActiveIndex];
          const navRect = timelineNav.getBoundingClientRect();
          const itemRect = activeItem.getBoundingClientRect();

          timelineNav.scrollTo({
            left: itemRect.left - navRect.left - (navRect.width / 2) + (itemRect.width / 2),
            behavior: 'smooth'
          });
        }
      }
    }

    function handleTimelineClick(event) {
      const clickedItem = event.currentTarget;
      const year = clickedItem.getAttribute('data-year');
      const targetSection = document.getElementById(`year-${year}`);

      if (targetSection) {
        // Calculate the correct scroll position accounting for all offsets
        const scrollPosition = targetSection.offsetTop - timelineSectionTop + navStickyTop;

        window.scrollTo({
          top: scrollPosition,
          behavior: 'smooth'
        });
      }
    }

    navItems.forEach(item => {
      item.addEventListener('click', handleTimelineClick);
    });

    window.addEventListener('scroll', highlightCurrentSection);
    window.addEventListener('resize', highlightCurrentSection);

    highlightCurrentSection();
  });
</script>