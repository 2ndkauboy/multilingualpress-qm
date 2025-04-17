<?php declare( strict_types=1 );

/**
 * MultilingualPress translations collector.
 *
 * @package multilingualpress-qm
 */

use Inpsyde\MultilingualPress\Framework\Api\Translation;
use Inpsyde\MultilingualPress\Framework\Api\TranslationSearchArgs;
use Inpsyde\MultilingualPress\Framework\WordpressContext;
use Inpsyde\MultilingualPress\Framework\Api\Translations;
use function Inpsyde\MultilingualPress\resolve;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @extends QM_DataCollector<QM_Data_Conditionals>
 */
class QM_Collector_MLP_Translations extends QM_DataCollector {

	public $id = 'mlp_translations';

	public function get_storage(): QM_Data {
		return new QM_Data_MLP_Translations();
	}

	/**
	 * Get all translations of the current content.
	 *
	 * @return void
	 */
	public function process(): void {
		$args = TranslationSearchArgs::forContext(
			new WordpressContext()
		)->forSiteId( get_current_blog_id() )->includeBase();

		/** @var Translation[] $translations */
		$translations   = resolve( Translations::class )->searchTranslations( $args );
		$current_locale = get_locale();

		$this->data->translations = [];
		foreach ( $translations as $translation ) {
			if ( $translation->language()->locale() !== $current_locale && $translation->remoteContentId() !== 0 ) {
				$this->data->translations[] = $translation;
			}
		}
	}
}

/**
 * @param array<string, QM_Collector> $collectors
 * @param QueryMonitor                $qm
 *
 * @return array<string, QM_Collector>
 */
function register_qm_collector_mlp_translations( array $collectors, QueryMonitor $qm ) {
	$collectors['mlp_translations'] = new QM_Collector_MLP_Translations();

	return $collectors;
}

add_filter( 'qm/collectors', 'register_qm_collector_mlp_translations', 10, 2 );
