<?php declare( strict_types=1 );

/**
 * Request and translations headers output for HTML pages.
 *
 * @package multilingualpress-qm
 */

use Inpsyde\MultilingualPress\Framework\Api\Translation;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class QM_Output_Html_MLP_Translations extends QM_Output_Html {

	/**
	 * Collector instance.
	 *
	 * @var QM_Collector_Raw_Request Collector.
	 */
	protected $collector;

	public function __construct( QM_Collector $collector ) {
		parent::__construct( $collector );
		add_filter( 'qm/output/panel_menus', array( $this, 'panel_menu' ), 20 );
	}

	/**
	 * Collector name.
	 *
	 * This is unused.
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Translations', 'multilingualpress' );
	}

	/**
	 * @return void
	 */
	public function output() {
		/** @var QM_Data_MLP_Translations $data */
		$data = $this->collector->get_data();

		if ( empty( $data->translations ) ) {
			$this->before_non_tabular_output();

			$notice = __( 'No translations for this content.', 'multilingualpress-qm' );
			echo $this->build_notice( $notice ); // WPCS: XSS ok.

			$this->after_non_tabular_output();

			return;
		}

		$id = sprintf( 'qm-%s', $this->collector->id );

		$this->before_tabular_output( $id );

		$this->output_translation_table( $data->translations );

		$this->after_tabular_output();
	}

	/**
	 * @param array<int, Translation> $translations
	 *
	 * @return void
	 */
	protected function output_translation_table( array $translations ): void {
		echo '<thead>';
		echo '<tr>';
		printf( '<th>%s</th>', esc_html__( 'Locale', 'multilingualpress-qm' ) );
		printf( '<th>%s</th>', esc_html__( 'Title', 'multilingualpress-qm' ) );
		printf( '<th>%s</th>', esc_html__( 'Post ID', 'multilingualpress-qm' ) );
		printf( '<th>%s</th>', esc_html__( 'Site ID', 'multilingualpress-qm' ) );
		echo '</tr>';
		echo '<tbody>';

		foreach ( $translations as $translation ) {
			echo '<tr>';
			printf( '<th scope="row"><code>%s</code></th>', esc_html( $translation->language()->locale() ) );
			printf(
				'<td class="qm-ltr">%s</td>',
				$this->build_link(
					$translation->remoteUrl(),
					$translation->remoteTitle()
				)
			);
			printf( '<td class="qm-num">%s</td>', esc_html( $translation->remoteContentId() ) );
			printf( '<td class="qm-num">%s</td>', esc_html( $translation->remoteSiteId() ) );
			echo '</tr>';
		}

		echo '</tbody>';
	}

	/**
	 * @param array<string, mixed[]> $menu
	 *
	 * @return array<string, mixed[]>
	 */
	public function panel_menu( array $menu ) {
		if ( ! isset( $menu['qm-languages'] ) ) {
			return $menu;
		}

		/** @var QM_Data_MLP_Translations $data */
		$data = $this->collector->get_data();

		$menu['qm-languages']['children'][] = $this->menu(
			[
				'title' => sprintf(
					esc_html__( 'Content Translations (%s)', 'multilingualpress-qm' ),
					number_format_i18n( count( $data->translations ) )
				)
			]
		);

		return $menu;
	}
}

/**
 * @param array<string, QM_Output> $output
 * @param QM_Collectors            $collectors
 *
 * @return array<string, QM_Output>
 */
function register_qm_output_html_mlp_translations( array $output, QM_Collectors $collectors ) {
	$collector = QM_Collectors::get( 'mlp_translations' );
	if ( $collector ) {
		$output['mlp_translations'] = new QM_Output_Html_MLP_Translations( $collector );
	}

	return $output;
}

add_filter( 'qm/outputter/html', 'register_qm_output_html_mlp_translations', 50, 2 );
