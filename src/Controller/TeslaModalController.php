<?php

// phpcs:ignoreFile
// This is all Drupal Console generated code.
// Commenting linting doesn't apply.
namespace Drupal\tesla_modal\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\tesla_modal\Entity\TeslaModalInterface;

/**
 * Class TeslaModalController.
 *
 *  Returns responses for Modal routes.
 */
class TeslaModalController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Modal  revision.
   *
   * @param int $tesla_modal_revision
   *   The Modal  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($tesla_modal_revision) {
    $tesla_modal = static::entityTypeManager()->getStorage('tesla_modal')->loadRevision($tesla_modal_revision);
    $view_builder = static::entityTypeManager()->getViewBuilder('tesla_modal');

    return $view_builder->view($tesla_modal);
  }

  /**
   * Page title callback for a Modal  revision.
   *
   * @param int $tesla_modal_revision
   *   The Modal  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($tesla_modal_revision) {
    $tesla_modal = static::entityTypeManager()->getStorage('tesla_modal')->loadRevision($tesla_modal_revision);
    return $this->t('Revision of %title from %date', ['%title' => $tesla_modal->label(), '%date' =>  \Drupal::service('date.formatter')->format($tesla_modal->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Modal .
   *
   * @param \Drupal\tesla_modal\Entity\TeslaModalInterface $tesla_modal
   *   A Modal  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(TeslaModalInterface $tesla_modal) {
    $account = $this->currentUser();
    $langcode = $tesla_modal->language()->getId();
    $langname = $tesla_modal->language()->getName();
    $languages = $tesla_modal->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $tesla_modal_storage = static::entityTypeManager()->getStorage('tesla_modal');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $tesla_modal->label()]) : $this->t('Revisions for %title', ['%title' => $tesla_modal->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all modal revisions") || $account->hasPermission('administer modal entities')));
    $delete_permission = (($account->hasPermission("delete all modal revisions") || $account->hasPermission('administer modal entities')));

    $rows = [];

    $vids = $tesla_modal_storage->revisionIds($tesla_modal);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\tesla_modal\TeslaModalInterface $revision */
      $revision = $tesla_modal_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $tesla_modal->getRevisionId()) {
          $link = \Drupal::service('link_generator')->generate($date, new Url('entity.tesla_modal.revision', ['tesla_modal' => $tesla_modal->id(), 'tesla_modal_revision' => $vid]));
        }
        else {
          $link = $tesla_modal->toLink($date)->toString();
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => \Drupal::service('renderer')->renderPlain($username),
              'message' => ['#markup' => $revision->getRevisionLogMessage(), '#allowed_tags' => Xss::getHtmlTagList()],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.tesla_modal.translation_revert', ['tesla_modal' => $tesla_modal->id(), 'tesla_modal_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.tesla_modal.revision_revert', ['tesla_modal' => $tesla_modal->id(), 'tesla_modal_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.tesla_modal.revision_delete', ['tesla_modal' => $tesla_modal->id(), 'tesla_modal_revision' => $vid]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['tesla_modal_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
