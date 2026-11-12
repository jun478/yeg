<?php
/** Return the existing Google Maps boarding locations shared by both guides. */
function yeg_get_bus_boarding_points() {
	return array(
		array(
			'number' => '01',
			'name'   => '宮古島市公設市場前',
			'query'  => '宮古島市公設市場',
			'url'    => 'https://www.google.com/maps/place/%E5%85%AC%E8%A8%AD%E5%B8%82%E5%A0%B4%E5%89%8D/@24.8039798,125.2787759,417m/data=!3m1!1e3!4m14!1m7!3m6!1s0x34f4538b127c2599:0x19097169989fdfed!2z5a6u5Y-k5bO25biC5YWs6Kit5biC5aC0!8m2!3d24.8039621!4d125.2787251!16s%2Fg%2F11b86xgq9w!3m5!1s0x34f4538b729fee33:0xb52c1be6809fd634!8m2!3d24.8043094!4d125.2787354!16s%2Fg%2F11hcjtr59y?entry=ttu&g_ep=EgoyMDI2MTAyOC4wIKXMDSoASAFQAw%3D%3D',
		),
		array(
			'number' => '02',
			'name'   => '旧宮古島市役所前',
			'query'  => '旧宮古島市役所',
			'url'    => 'https://www.google.com/maps/place/%E5%B8%82%E5%BD%B9%E6%89%80%E5%89%8D%EF%BC%88%E5%AE%AE%E5%8F%A4%E5%B3%B6%E5%B8%82%EF%BC%89%EF%BC%88%E3%83%90%E3%82%B9%EF%BC%89%EF%BC%88%E5%AE%AE%E5%8F%A4%E7%A9%BA%E6%B8%AF%E7%B7%9A%E3%83%BB%E4%B8%8B%E3%82%8A%EF%BC%89/@24.8055312,125.2808549,209m/data=!3m2!1e3!4b1!4m6!3m5!1s0x34f453f3457037e9:0xa8d9742447beec8e!8m2!3d24.80553!4d125.2815!16s%2Fg%2F11hcjtrqsp?entry=ttu&g_ep=EgoyMDI2MTAyOC4wIKXMDSoASAFQAw%3D%3D',
		),
		array(
			'number' => '03',
			'name'   => '海空すこやか公園 駐車場',
			'query'  => '海空すこやか公園 駐車場',
			'url'    => 'https://www.google.com/maps/place/%E3%83%91%E3%82%A4%E3%83%8A%E3%82%AC%E3%83%9E%E6%B5%B7%E7%A9%BA%E3%81%99%E3%81%93%E3%82%84%E3%81%8B%E5%85%AC%E5%9C%92/@24.8004495,125.2679918,229m/data=!3m1!1e3!4m6!3m5!1s0x34f45479ae34c03d:0x488372e9a2dd5827!8m2!3d24.800284!4d125.2676507!16s%2Fg%2F11gd3bt8wj?entry=ttu&g_ep=EgoyMDI2MTEwMy4wIKXMDSoASAFQAw%3D%3D',
		),
	);
}

/** Render a linked boarding location, preserving other place names as plain text. */
function yeg_bus_boarding_point_link( $name ) {
	$aliases = array(
		'宮古島市公設市場'          => '01',
		'宮古島公設市場'            => '01',
		'宮古島市公設市場前'        => '01',
		'旧市役所'                  => '02',
		'旧宮古島市役所'            => '02',
		'旧宮古島市役所前'          => '02',
		'海空すこやか公園 駐車場'   => '03',
	);
	if ( isset( $aliases[ $name ] ) ) {
		foreach ( yeg_get_bus_boarding_points() as $point ) {
			if ( $aliases[ $name ] === $point['number'] ) {
				return '<a class="yeg-bus-boarding-map-link" href="' . esc_url( $point['url'] ) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr( $name . 'のGoogleマップを開く（別タブ）' ) . '">' . esc_html( $name ) . '</a>';
			}
		}
	}
	return esc_html( $name );
}

/**
 * Return the shuttle bus guide data available to the shortcode.
 *
 * @return array
 */
function yeg_get_bus_guide_registry() {
	return array(
		'01' => array(
			'page_label'  => 'Page 01',
			'event'       => '会員総会 輸送計画',
			'direction'   => '午前：JTAドームへ輸送',
			'destination' => 'JTAドーム',
			'image'       => array(
				'file'         => '2026/10/' . rawurlencode( 'スライド3.jpeg' ),
				'alt'          => '会員総会のJTAドーム行きシャトルバス乗車場所案内図',
				'dialog_label' => '会員総会のシャトルバス乗車場所案内図を拡大表示',
			),
			'routes'      => array(
				array(
					'number'         => '01',
					'theme'          => 'orange',
					'departure_name' => '宮古島市公設市場',
					'times'          => array(
						array( 'buses' => '1～3号車', 'departure' => '8:00', 'arrival' => '8:20' ),
						array( 'buses' => '4～6号車', 'departure' => '8:10', 'arrival' => '8:30' ),
						array( 'buses' => '7～8号車', 'departure' => '8:20', 'arrival' => '8:40' ),
					),
				),
				array(
					'number'         => '02',
					'theme'          => 'blue',
					'departure_name' => '旧市役所',
					'times'          => array(
						array( 'buses' => '9～11号車', 'departure' => '8:05', 'arrival' => '8:25' ),
						array( 'buses' => '12～14号車', 'departure' => '8:10', 'arrival' => '8:30' ),
						array( 'buses' => '15～16号車', 'departure' => '8:15', 'arrival' => '8:35' ),
					),
				),
				array(
					'number'         => '03',
					'theme'          => 'green',
					'departure_name' => '海空すこやか公園 駐車場',
					'times'          => array(
						array( 'buses' => '17～18号車', 'departure' => '8:00', 'arrival' => '8:20' ),
						array( 'buses' => '19～20号車', 'departure' => '8:15', 'arrival' => '8:35' ),
					),
				),
			),
			'note'        => '交通状況により前後する可能性があります。余裕をもった運行計画を行います。',
		),
		'02' => array(
			'page_label'  => 'Page 02',
			'event'       => '開会式 輸送計画',
			'direction'   => '午前：JTAドームへ輸送',
			'destination' => 'JTAドーム',
			'routes'      => array(
				array(
					'number'         => '01',
					'theme'          => 'orange',
					'departure_name' => '宮古島市公設市場',
					'show_service'   => true,
					'times'          => array(
						array( 'service' => '1便目', 'buses' => '21～23号車', 'departure' => '8:30', 'arrival' => '8:50' ),
						array( 'service' => '1便目', 'buses' => '1～3号車', 'departure' => '8:50', 'arrival' => '9:15' ),
						array( 'service' => '1便目', 'buses' => '4～6号車', 'departure' => '9:00', 'arrival' => '9:20' ),
						array( 'service' => '2便目', 'buses' => '21～23号車', 'departure' => '9:20', 'arrival' => '9:40' ),
						array( 'service' => '2便目', 'buses' => '1～3号車', 'departure' => '9:40', 'arrival' => '10:00' ),
						array( 'service' => '2便目', 'buses' => '4～6号車', 'departure' => '9:50', 'arrival' => '10:10' ),
					),
				),
				array(
					'number'         => '02',
					'theme'          => 'blue',
					'departure_name' => '旧宮古島市役所',
					'show_service'   => true,
					'times'          => array(
						array( 'service' => '1便目', 'buses' => '24～26号車', 'departure' => '8:30', 'arrival' => '8:50' ),
						array( 'service' => '1便目', 'buses' => '10～12号車', 'departure' => '8:55', 'arrival' => '9:15' ),
						array( 'service' => '1便目', 'buses' => '13～14号車', 'departure' => '9:00', 'arrival' => '9:20' ),
						array( 'service' => '2便目', 'buses' => '24～26号車', 'departure' => '9:20', 'arrival' => '9:40' ),
						array( 'service' => '2便目', 'buses' => '10～12号車', 'departure' => '9:45', 'arrival' => '10:05' ),
						array( 'service' => '2便目', 'buses' => '13～14号車', 'departure' => '9:50', 'arrival' => '10:10' ),
					),
				),
				array(
					'number'         => '03',
					'theme'          => 'green',
					'departure_name' => '海空すこやか公園 駐車場',
					'show_service'   => true,
					'times'          => array(
						array( 'service' => '1便目', 'buses' => '27号車', 'departure' => '9:00', 'arrival' => '9:20' ),
						array( 'service' => '1便目', 'buses' => '28号車', 'departure' => '9:15', 'arrival' => '9:35' ),
						array( 'service' => '1便目', 'buses' => '29号車', 'departure' => '9:30', 'arrival' => '9:50' ),
						array( 'service' => '1便目', 'buses' => '30号車', 'departure' => '9:45', 'arrival' => '10:05' ),
					),
				),
			),
			'note'        => '交通状況により前後する可能性があります。余裕をもった運行計画を行います。',
		),
		'03' => array(
			'page_label'  => 'Page 03',
			'event'       => '当年度会長研修＆国際交流研究会輸送計画',
			'direction'   => '研修終了後：漲水シンボル緑地へ輸送',
			'destination' => '漲水シンボル緑地',
			'image'       => array(
				'source'       => 'theme',
				'file'         => 'img/transport/page-03-president-exchange.jpg',
				'alt'          => '当年度会長研修と国際交流研究会の輸送計画',
				'dialog_label' => '当年度会長研修と国際交流研究会の輸送計画を拡大表示',
			),
			'routes'      => array(
				array(
					'number'         => '01',
					'theme'          => 'blue',
					'departure_name' => 'JTAドーム',
					'times'          => array(
						array( 'buses' => '22～25号車', 'departure' => '17:35', 'arrival' => '17:55' ),
						array( 'buses' => '26～29号車', 'departure' => '17:40', 'arrival' => '18:00' ),
						array( 'buses' => '18～20号車', 'departure' => '17:45', 'arrival' => '18:05' ),
					),
				),
			),
			'note'        => '交通状況により前後する可能性があります。余裕をもった運行計画を行います。',
		),
		'04' => array(
			'page_label'  => 'Page 04',
			'event'       => '事務局研修 輸送計画',
			'direction'   => '往路：平良港マリンターミナルビル（大会議室）へ輸送',
			'destination' => '平良港マリンターミナルビル（大会議室）',
			'image'       => array(
				'source'       => 'theme',
				'file'         => 'img/transport/page-04-secretariat.jpg',
				'alt'          => '事務局研修のJTAドーム発平良港マリンターミナルビル（大会議室）行き輸送計画',
				'dialog_label' => '事務局研修の輸送計画を拡大表示',
				'badge'        => 'Page 04',
			),
			'routes'      => array(
				array(
					'number'         => '01',
					'theme'          => 'blue',
					'departure_name' => 'JTAドーム',
					'times'          => array(
						array( 'buses' => '1号車', 'departure' => '13:00', 'arrival' => '13:20' ),
						array( 'buses' => '2号車', 'departure' => '13:05', 'arrival' => '13:25' ),
						array( 'buses' => '3号車', 'departure' => '13:10', 'arrival' => '13:30' ),
					),
				),
			),
			'follow_up'   => array(
				'label' => '研修終了後',
				'text'  => '平良港マリンターミナルビル（大会議室）から漲水シンボル緑地へ徒歩で移動してください。',
			),
			'note'        => '交通状況により前後する可能性があります。余裕をもった運行計画を行います。',
		),
		'05' => array(
			'page_label'  => 'Page 05',
			'event'       => 'OB・OG研修＆会員増強研究会 輸送計画',
			'direction'   => '往路・研修終了後：各会場へ輸送',
			'destination' => '各研修会場',
			'image'       => array(
				'source'       => 'theme',
				'file'         => 'img/transport/page-05-obog-member-growth.jpg',
				'alt'          => 'OB・OG研修と会員増強研究会の輸送計画',
				'dialog_label' => 'OB・OG研修と会員増強研究会の輸送計画を拡大表示',
			),
			'routes'      => array(
				array(
					'number'           => '01',
					'theme'            => 'blue',
					'departure_name'   => 'JTAドーム',
					'destination_name' => '各研修会場',
					'times'            => array(
						array( 'buses' => '7～9号車', 'departure' => '14:35', 'arrival' => '14:55' ),
					),
				),
				array(
					'number'           => '02',
					'theme'            => 'orange',
					'departure_name'   => '各研修会場',
					'destination_name' => '漲水シンボル緑地',
					'times'            => array(
						array( 'buses' => '1～3号車', 'departure' => '18:05', 'arrival' => '18:10' ),
					),
				),
			),
			'note'        => '会員増強研究会はヒルトン沖縄宮古島リゾート、OB・OG研修はYard miyakojimaです。時刻表は両研修共通の配車計画です。行先別の号車・乗車場所は現地スタッフへご確認ください。交通状況により前後する可能性があります。余裕をもった運行計画を行います。',
		),
		'06' => array(
			'page_label'  => 'Page 06',
			'event'       => '広報DX研究会・YEG経済圏構築研究会 輸送計画',
			'direction'   => '往路・研修終了後：各会場へ輸送',
			'destination' => '未来創造センター',
			'image'       => array(
				'source'       => 'theme',
				'file'         => 'img/transport/page-06-pr-dx-economic-zone.jpg',
				'alt'          => '広報DX研究会・YEG経済圏構築研究会の輸送計画',
				'dialog_label' => '広報DX研究会・YEG経済圏構築研究会の輸送計画を拡大表示',
			),
			'routes'      => array(
				array(
					'number'           => '01',
					'theme'            => 'blue',
					'departure_name'   => 'JTAドーム',
					'destination_name' => '未来創造センター',
					'times'            => array(
						array( 'buses' => '1・2号車', 'departure' => '14:35', 'arrival' => '14:50' ),
						array( 'buses' => '3・4号車', 'departure' => '14:40', 'arrival' => '14:55' ),
					),
				),
				array(
					'number'           => '02',
					'theme'            => 'orange',
					'departure_name'   => '未来創造センター',
					'destination_name' => '漲水シンボル緑地',
					'times'            => array(
						array( 'buses' => '1～3号車', 'departure' => '17:35', 'arrival' => '17:45' ),
						array( 'buses' => '4号車', 'departure' => '17:40', 'arrival' => '17:50' ),
					),
				),
			),
			'note'        => '交通状況により前後する可能性があります。余裕をもった運行計画を行います。',
		),
		'07' => array(
			'page_label'  => 'Page 07',
			'event'       => '政策提言研究会 輸送計画',
			'direction'   => '往路・研修終了後：各会場へ輸送',
			'destination' => 'ホテルシギラミラージュ',
			'image'       => array(
				'source'       => 'theme',
				'file'         => 'img/transport/page-07-policy.jpg',
				'alt'          => '政策提言研究会の輸送計画',
				'dialog_label' => '政策提言研究会の輸送計画を拡大表示',
			),
			'routes'      => array(
				array(
					'number'           => '01',
					'theme'            => 'blue',
					'departure_name'   => 'JTAドーム',
					'destination_name' => 'ホテルシギラミラージュ',
					'times'            => array(
						array( 'buses' => '26・27号車', 'departure' => '14:35', 'arrival' => '14:55' ),
						array( 'buses' => '28号車', 'departure' => '14:40', 'arrival' => '15:00' ),
					),
				),
				array(
					'number'           => '02',
					'theme'            => 'orange',
					'departure_name'   => 'ホテルシギラミラージュ（コンベンションホール1F）',
					'destination_name' => '漲水シンボル緑地',
					'times'            => array(
						array( 'buses' => '13～15号車', 'departure' => '17:35', 'arrival' => '18:05' ),
					),
				),
			),
			'note'        => '交通状況により前後する可能性があります。余裕をもった運行計画を行います。',
		),
		'08' => array(
			'page_label'  => 'Page 08',
			'event'       => '研修D・G・I 共通輸送計画',
			'direction'   => '往路・研修終了後：各会場へ輸送',
			'destination' => 'ホテルシギラミラージュ',
			'image'       => array(
				'source'       => 'theme',
				'file'         => 'img/transport/page-08-dgi-common.jpg',
				'alt'          => '研修D・G・Iの共通輸送計画',
				'dialog_label' => '研修D・G・Iの共通輸送計画を拡大表示',
			),
			'routes'      => array(
				array(
					'number'           => '01',
					'theme'            => 'blue',
					'departure_name'   => 'JTAドーム',
					'destination_name' => 'ホテルシギラミラージュ',
					'times'            => array(
						array( 'buses' => '24号車', 'departure' => '14:35', 'arrival' => '14:55' ),
						array( 'buses' => '25・30号車', 'departure' => '14:40', 'arrival' => '15:00' ),
					),
				),
				array(
					'number'           => '02',
					'theme'            => 'green',
					'departure_name'   => 'JTAドーム',
					'destination_name' => 'ホテルブリーズベイマリーナ',
					'times'            => array(
						array( 'buses' => '20号車', 'departure' => '14:35', 'arrival' => '14:55' ),
					),
				),
				array(
					'number'           => '03',
					'theme'            => 'blue',
					'departure_name'   => 'JTAドーム',
					'destination_name' => 'シギラセブンマイルズリゾート 島唄',
					'times'            => array(
						array( 'buses' => '22号車', 'departure' => '14:35', 'arrival' => '14:55' ),
						array( 'buses' => '23号車', 'departure' => '14:40', 'arrival' => '15:00' ),
					),
				),
				array(
					'number'           => '04',
					'theme'            => 'orange',
					'departure_name'   => 'ホテルシギラミラージュ（コンベンションホール2F）',
					'destination_name' => '漲水シンボル緑地',
					'times'            => array(
						array( 'buses' => '7号車', 'departure' => '17:35', 'arrival' => '18:05' ),
						array( 'buses' => '8・17号車', 'departure' => '17:40', 'arrival' => '18:10' ),
					),
				),
				array(
					'number'           => '05',
					'theme'            => 'orange',
					'departure_name'   => 'ホテルブリーズベイマリーナ',
					'destination_name' => '漲水シンボル緑地',
					'times'            => array(
						array( 'buses' => '9号車', 'departure' => '17:35', 'arrival' => '18:05' ),
					),
				),
				array(
					'number'           => '06',
					'theme'            => 'orange',
					'departure_name'   => 'シギラセブンマイルズリゾート 島唄',
					'destination_name' => '漲水シンボル緑地',
					'times'            => array(
						array( 'buses' => '11号車', 'departure' => '17:35', 'arrival' => '18:05' ),
						array( 'buses' => '12号車', 'departure' => '17:40', 'arrival' => '18:10' ),
					),
				),
			),
			'note'        => '交通状況により前後する可能性があります。余裕をもった運行計画を行います。',
		),
		'09' => array(
			'page_label'  => 'Page 09',
			'event'       => 'スタートアップ支援研究会 輸送計画',
			'direction'   => '往路：ホテルアトールエメラルド宮古島へ輸送',
			'destination' => 'ホテルアトールエメラルド宮古島',
			'image'       => array(
				'source'       => 'theme',
				'file'         => 'img/transport/page-09-startup.jpg',
				'alt'          => 'スタートアップ支援研究会の輸送計画',
				'dialog_label' => 'スタートアップ支援研究会の輸送計画を拡大表示',
			),
			'routes'      => array(
				array(
					'number'           => '01',
					'theme'            => 'blue',
					'departure_name'   => 'JTAドーム',
					'destination_name' => 'ホテルアトールエメラルド宮古島',
					'times'            => array(
						array( 'buses' => '15・16号車', 'departure' => '14:40', 'arrival' => '15:00' ),
					),
				),
			),
			'follow_up'   => array(
				'label' => '研修終了後',
				'text'  => 'ホテルアトールエメラルド宮古島から漲水シンボル緑地へ徒歩で移動してください。',
			),
			'note'        => '交通状況により前後する可能性があります。余裕をもった運行計画を行います。',
		),
		'10' => array(
			'page_label'  => 'Page 10',
			'event'       => '安里繁信氏講演 輸送計画',
			'direction'   => '往路：マティダ市民劇場へ輸送',
			'destination' => 'マティダ市民劇場',
			'image'       => array(
				'source'       => 'theme',
				'file'         => 'img/transport/page-10-asato.jpg',
				'alt'          => '安里繁信氏講演のJTAドーム発マティダ市民劇場行き輸送計画',
				'dialog_label' => '安里繁信氏講演の輸送計画を拡大表示',
				'badge'        => 'Page 10',
			),
			'routes'      => array(
				array(
					'number'         => '01',
					'theme'          => 'blue',
					'departure_name' => 'JTAドーム',
					'times'          => array(
						array( 'buses' => '26・27・20・21号車', 'departure' => '13:40', 'arrival' => '14:00' ),
						array( 'buses' => '22・23号車', 'departure' => '13:45', 'arrival' => '14:05' ),
					),
				),
			),
			'follow_up'   => array(
				'label' => '研修終了後',
				'text'  => 'マティダ市民劇場から漲水シンボル緑地へ徒歩で移動してください。',
			),
			'note'        => '交通状況により前後する可能性があります。余裕をもった運行計画を行います。',
		),
		'11' => array(
			'page_label'  => 'Page 11',
			'event'       => '次年度会長研修 輸送計画',
			'direction'   => '往路：JTAドームへ輸送',
			'destination' => 'JTAドーム',
			'image'       => array(
				'source'       => 'theme',
				'file'         => 'img/transport/page-11-next-president.jpg',
				'alt'          => '次年度会長研修の宮古島市公設市場発JTAドーム行き輸送計画',
				'dialog_label' => '次年度会長研修の輸送計画を拡大表示',
			),
			'routes'      => array(
				array(
					'number'           => '01',
					'theme'            => 'blue',
					'departure_name'   => '宮古島市公設市場',
					'destination_name' => 'JTAドーム',
					'times'            => array(
						array( 'buses' => '1・2号車', 'departure' => '8:00', 'arrival' => '8:20' ),
						array( 'buses' => '3・4号車', 'departure' => '8:05', 'arrival' => '8:25' ),
						array( 'buses' => '5・6号車', 'departure' => '8:10', 'arrival' => '8:30' ),
						array( 'buses' => '7・8号車', 'departure' => '8:15', 'arrival' => '8:35' ),
						array( 'buses' => '9・10号車', 'departure' => '8:20', 'arrival' => '8:40' ),
					),
				),
			),
			'note'        => '交通状況により前後する可能性があります。余裕をもった運行計画を行います。',
		),
		'12' => array(
			'page_label' => 'Page 12',
			'event'      => '特別研修A（副会長）輸送計画',
			'image'      => array(
				'source'       => 'theme',
				'file'         => 'img/transport/page-12-vice-president.jpg',
				'alt'          => '特別研修A（副会長）のホテルアトールエメラルド宮古島発JTAドーム行き輸送計画',
				'dialog_label' => '特別研修A（副会長）の輸送計画を拡大表示',
			),
			'notice'     => '現地行きのバスはございません、現地集合でお願いします。研修終了後にJTAドーム行きのバスを用意していますので、現地スタッフまでお問い合わせ下さい。',
		),
		'13' => array(
			'page_label'  => 'Page 13',
			'event'       => '特別研修B（専務）輸送計画',
			'direction'   => '往路（JTAドーム経由）・研修終了後：各会場へ輸送',
			'destination' => '未来創造センター',
			'image'       => array(
				'source'       => 'theme',
				'file'         => 'img/transport/page-13-executive-director.jpg',
				'alt'          => '特別研修B（専務）の輸送計画',
				'dialog_label' => '特別研修B（専務）の輸送計画を拡大表示',
			),
			'routes'      => array(
				array(
					'number'           => '01',
					'theme'            => 'orange',
					'departure_name'   => '旧市役所',
					'destination_name' => '未来創造センター',
					'times'            => array(
						array( 'buses' => '11～13号車', 'departure' => '8:00', 'arrival' => '8:55' ),
					),
				),
				array(
					'number'           => '02',
					'theme'            => 'blue',
					'departure_name'   => '海空すこやか公園 駐車場',
					'destination_name' => '未来創造センター',
					'times'            => array(
						array( 'buses' => '20号車', 'departure' => '8:00', 'arrival' => '8:55' ),
					),
				),
				array(
					'number'           => '03',
					'theme'            => 'green',
					'departure_name'   => '未来創造センター（多目的ホール）',
					'destination_name' => 'JTAドーム',
					'times'            => array(
						array( 'buses' => '11～15号車', 'departure' => '11:40', 'arrival' => '11:55' ),
						array( 'buses' => '16～20号車', 'departure' => '11:45', 'arrival' => '12:00' ),
					),
				),
			),
			'follow_up'   => array(
				'label' => '往路の経由地',
				'text'  => '往路は旧市役所・海空すこやか公園を8:00に出発し、JTAドーム8:20着・8:40発、未来創造センター8:55着です。',
			),
			'note'        => '交通状況により前後する可能性があります。余裕をもった運行計画を行います。',
		),
		'14' => array(
			'page_label' => 'Page 14',
			'event'      => '特別研修C（3大事業）輸送計画',
			'image'      => array(
				'source'       => 'theme',
				'file'         => 'img/transport/page-14-three-major-projects.jpg',
				'alt'          => '特別研修C（3大事業）の輸送計画',
				'dialog_label' => '特別研修C（3大事業）の輸送計画を拡大表示',
			),
			'direction'   => '研修終了後：JTAドームへ輸送',
			'destination' => 'JTAドーム',
			'routes'      => array(
				array(
					'number'           => '01',
					'theme'            => 'green',
					'departure_name'   => '平良港マリンターミナルビル（大会議室）',
					'destination_name' => 'JTAドーム',
					'times'            => array(
						array( 'buses' => '17号車', 'departure' => '11:10', 'arrival' => '11:30' ),
						array( 'buses' => '14～16・18号車', 'departure' => '11:15', 'arrival' => '11:35' ),
					),
				),
			),
			'note'        => '交通状況により前後する可能性があります。余裕をもった運行計画を行います。',
			'notice'     => '現地行きのバスはございません、現地集合でお願いします。研修終了後にJTAドーム行きのバスを用意していますので、現地スタッフまでお問い合わせ下さい。',
		),
		'15' => array(
			'page_label'  => 'Page 15',
			'event'       => '分科会D・E・I 共通輸送計画',
			'direction'   => '往路・研修終了後：各会場へ輸送',
			'destination' => 'ヒルトン沖縄宮古島リゾート',
			'image'       => array(
				'source'       => 'theme',
				'file'         => 'img/transport/page-15-breakout-dei.jpg',
				'alt'          => '分科会D・E・Iの共通輸送計画',
				'dialog_label' => '分科会D・E・Iの共通輸送計画を拡大表示',
			),
			'routes'      => array(
				array(
					'number'           => '01',
					'theme'            => 'blue',
					'departure_name'   => '宮古島公設市場',
					'destination_name' => 'ヒルトン沖縄宮古島リゾート',
					'times'            => array(
						array( 'buses' => '23号車', 'departure' => '8:55', 'arrival' => '9:10' ),
					),
				),
				array(
					'number'           => '02',
					'theme'            => 'orange',
					'departure_name'   => '宮古島公設市場',
					'destination_name' => 'オルタナティブファーム宮古',
					'times'            => array(
						array( 'buses' => '21号車', 'departure' => '8:50', 'arrival' => '9:10' ),
					),
				),
				array(
					'number'           => '03',
					'theme'            => 'green',
					'departure_name'   => '宮古島公設市場',
					'destination_name' => '地下ダム資料館・福里ダム',
					'times'            => array(
						array( 'buses' => '28号車', 'departure' => '8:35', 'arrival' => '9:10' ),
					),
				),
				array(
					'number'           => '04',
					'theme'            => 'green',
					'departure_name'   => 'オルタナティブファーム宮古',
					'destination_name' => 'JTAドーム',
					'times'            => array(
						array( 'buses' => '21号車', 'departure' => '11:40', 'arrival' => '11:55' ),
					),
				),
				array(
					'number'           => '05',
					'theme'            => 'green',
					'departure_name'   => '地下ダム資料館',
					'destination_name' => 'JTAドーム',
					'times'            => array(
						array( 'buses' => '28号車', 'departure' => '11:40', 'arrival' => '12:05' ),
					),
				),
			),
			'note'        => '交通状況により前後する可能性があります。余裕をもった運行計画を行います。',
		),
		'16' => array(
			'page_label'  => 'Page 16',
			'event'       => '分科会F・G・H 共通輸送計画',
			'direction'   => '往路・会場間移動・研修終了後：各会場へ輸送',
			'destination' => '狩俣マッチャーズ',
			'image'       => array(
				'source'       => 'theme',
				'file'         => 'img/transport/page-16-breakout-fgh.jpg',
				'alt'          => '分科会F・G・Hの共通輸送計画',
				'dialog_label' => '分科会F・G・Hの共通輸送計画を拡大表示',
			),
			'routes'      => array(
				array(
					'number'           => '01',
					'theme'            => 'blue',
					'departure_name'   => '宮古島公設市場',
					'destination_name' => '狩俣マッチャーズ',
					'times'            => array(
						array( 'buses' => '25号車', 'departure' => '8:45', 'arrival' => '9:10' ),
					),
				),
				array(
					'number'           => '02',
					'theme'            => 'orange',
					'departure_name'   => '宮古島公設市場',
					'destination_name' => '宮古神社',
					'times'            => array(
						array( 'buses' => '24号車', 'departure' => '9:00', 'arrival' => '9:05' ),
					),
				),
				array(
					'number'           => '03',
					'theme'            => 'green',
					'departure_name'   => '宮古島公設市場',
					'destination_name' => 'シーウッドホテル',
					'times'            => array(
						array( 'buses' => '22・27号車', 'departure' => '8:40', 'arrival' => '9:10' ),
					),
				),
				array(
					'number'           => '04',
					'theme'            => 'blue',
					'departure_name'   => '狩俣マッチャーズ',
					'destination_name' => '島の駅みやこ',
					'times'            => array(
						array( 'buses' => '25号車', 'departure' => '10:20', 'arrival' => '10:45' ),
					),
				),
				array(
					'number'           => '05',
					'theme'            => 'green',
					'departure_name'   => '島の駅みやこ',
					'destination_name' => 'JTAドーム',
					'times'            => array(
						array( 'buses' => '25号車', 'departure' => '11:30', 'arrival' => '11:50' ),
					),
				),
				array(
					'number'           => '06',
					'theme'            => 'green',
					'departure_name'   => '宮古神社',
					'destination_name' => 'JTAドーム',
					'times'            => array(
						array( 'buses' => '24号車', 'departure' => '11:40', 'arrival' => '12:00' ),
					),
				),
				array(
					'number'           => '07',
					'theme'            => 'green',
					'departure_name'   => 'シーウッドホテル',
					'destination_name' => 'JTAドーム',
					'times'            => array(
						array( 'buses' => '22・27号車', 'departure' => '11:45', 'arrival' => '12:15' ),
					),
				),
			),
			'note'        => '交通状況により前後する可能性があります。余裕をもった運行計画を行います。',
		),
		'17' => array(
			'page_label' => 'Page 17',
			'event'      => '基調講演（岩田松雄氏）輸送計画',
			'image'      => array(
				'source'       => 'theme',
				'file'         => 'img/transport/page-17-breakout-j.jpg',
				'alt'          => '基調講演（岩田松雄氏）のマティダ市民劇場発JTAドーム行き輸送計画',
				'dialog_label' => '基調講演（岩田松雄氏）の輸送計画を拡大表示',
			),
			'notice'     => '現地行きのバスはございません、現地集合でお願いします。研修終了後にJTAドーム行きのバスを用意していますので、現地スタッフまでお問い合わせ下さい。',
		),
		'18' => array(
			'page_label'  => 'Page 18',
			'event'       => '閉会式終了後 輸送計画',
			'direction'   => '閉会式終了後：宮古島市公設市場へ輸送',
			'destination' => '宮古島市公設市場',
			'routes'      => array(
				array(
					'number'           => '01',
					'theme'            => 'green',
					'departure_name'   => 'JTAドーム',
					'destination_name' => '宮古島市公設市場',
					'times'            => array(
						array( 'buses' => '6・19号車', 'departure' => '14:15', 'arrival' => '14:35' ),
						array( 'buses' => '6・19号車', 'departure' => '15:05', 'arrival' => '15:25' ),
					),
				),
			),
			'note'        => '交通状況により前後する可能性があります。余裕をもった運行計画を行います。',
		),
	);
}

/**
 * Render a decorative icon used by the bus guide.
 *
 * @param string $name Icon name.
 * @return string
 */
function yeg_bus_guide_icon( $name ) {
	$icons = array(
		'clock' => '<circle cx="12" cy="12" r="8.5"></circle><path d="M12 7.5v5l3.2 2"></path>',
		'users' => '<circle cx="9" cy="8" r="3"></circle><circle cx="16.5" cy="9" r="2.5"></circle><path d="M3.5 19c.5-4 2.4-6 5.5-6s5 2 5.5 6"></path><path d="M14.3 14c2.9 0 4.8 1.7 5.2 5"></path>',
		'bus'   => '<rect x="4" y="3.5" width="16" height="15" rx="2.5"></rect><path d="M4 9h16M7.5 14h.01M16.5 14h.01M7 18.5v2M17 18.5v2"></path>',
		'pin'   => '<path d="M20 10c0 5.5-8 11-8 11S4 15.5 4 10a8 8 0 1 1 16 0Z"></path><circle cx="12" cy="10" r="2.5"></circle>',
		'info'  => '<circle cx="12" cy="12" r="9"></circle><path d="M12 10.5V17M12 7h.01"></path>',
	);

	if ( ! isset( $icons[ $name ] ) ) {
		return '';
	}

	return '<svg class="yeg-bus-guide__icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">' . $icons[ $name ] . '</svg>';
}

/**
 * Render a shuttle bus guide selected from the static registry.
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function yeg_bus_guide_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'direction'  => '',
			'page'       => '',
			'route'      => '',
			'show_title' => 'true',
		),
		$atts,
		'yeg_bus_guide'
	);

	$registry = yeg_get_bus_guide_registry();
	$page     = (string) $atts['page'];

	if ( ! isset( $registry[ $page ] ) ) {
		return '';
	}

	$is_international_exchange = '03' === $page && is_singular( 'events' ) && 'day1-training-j-international-exchange-20261113' === get_post_field( 'post_name', get_the_ID() );

	$guide           = $registry[ $page ];
	$guide['routes'] = isset( $guide['routes'] ) && is_array( $guide['routes'] ) ? $guide['routes'] : array();
	if ( is_singular( 'events' ) ) {
		$post_slug = get_post_field( 'post_name', get_the_ID() );
		// Use event-specific manual images without changing other events' shared guides.
		if ( '16' === $page && 'breakout-f-20261114' === $post_slug ) {
			$guide['event']     = '分科会F 輸送計画';
			$guide['direction'] = '往路・会場間移動・研修終了後のバス運行';
			$guide['image']     = array(
				'source'       => 'theme',
				'file'         => 'img/transport/page-16-breakout-f-1111.png',
				'alt'          => '分科会Fの25号車運行案内：公設市場8時45分発・狩俣9時10分着、狩俣10時20分発・島の駅みやこ10時45分着、島の駅11時30分発・JTAドーム11時50分着',
				'dialog_label' => '分科会Fのシャトルバス運行案内を拡大表示',
			);
		}
		if ( '16' === $page && 'breakout-g-20261114' === $post_slug ) {
			$guide['event']     = '分科会G 輸送計画';
			$guide['direction'] = '往路・研修終了後：宮古神社・JTAドームへ輸送';
			$guide['image']     = array(
				'source'       => 'theme',
				'file'         => 'img/transport/page-16-breakout-g-1111.png',
				'alt'          => '分科会Gの24号車運行案内：公設市場9時発・宮古神社9時5分着、宮古神社11時40分発・JTAドーム12時着',
				'dialog_label' => '分科会Gのシャトルバス運行案内を拡大表示',
			);
		}
		// Shared timing does not assign specific buses to each venue.
		$venues = array(
			'05' => array(
				'day1-training-c-member-growth-20261113' => 'ヒルトン沖縄宮古島リゾート',
				'ob-og-program-20261113'                 => 'Yard miyakojima',
			),
			'06' => array(
				'day1-training-b-pr-dx-20261113'         => '未来創造センター（研修室）',
				'day1-training-f-economic-zone-20261113' => '未来創造センター（多目的ホール）',
			),
		);
		if ( isset( $venues[ $page ][ $post_slug ] ) ) {
			foreach ( $guide['routes'] as &$route ) {
				if ( '01' === $route['number'] ) {
					$route['destination_name'] = $venues[ $page ][ $post_slug ];
				} elseif ( '02' === $route['number'] ) {
					$route['departure_name'] = $venues[ $page ][ $post_slug ];
				}
			}
			unset( $route );
		}
	}

	$route_number    = sanitize_text_field( (string) $atts['route'] );
	$route_numbers   = array_map( 'trim', explode( ',', $route_number ) );
	$direction       = sanitize_text_field( (string) $atts['direction'] );
	$notice          = isset( $guide['notice'] ) ? (string) $guide['notice'] : '';

	if ( '' !== $direction ) {
		$guide['direction'] = $direction;
	}

	if ( '' !== $route_number ) {
		$guide['routes'] = array_values(
			array_filter(
				$guide['routes'],
				function ( $route ) use ( $route_numbers ) {
					return isset( $route['number'] ) && in_array( (string) $route['number'], $route_numbers, true );
				}
			)
		);

		if ( empty( $guide['routes'] ) ) {
			return '';
		}
	}

	$show_title  = ! in_array( strtolower( trim( (string) $atts['show_title'] ) ), array( '0', 'false', 'no', 'off' ), true );
	$title_id    = wp_unique_id( 'yeg-bus-guide-title-' );
	$image       = ! $is_international_exchange && isset( $guide['image'] ) && is_array( $guide['image'] ) ? $guide['image'] : null;
	$modal_id    = $image ? wp_unique_id( 'yeg-bus-guide-image-modal-' ) : '';
	$image_url   = '';
	$image_badge = $image && ! empty( $image['badge'] ) ? (string) $image['badge'] : '';

	if ( $image ) {
		if ( isset( $image['source'] ) && 'theme' === $image['source'] ) {
			$image_url = trailingslashit( get_stylesheet_directory_uri() ) . ltrim( $image['file'], '/' );
			$image_path = trailingslashit( get_stylesheet_directory() ) . ltrim( $image['file'], '/' );
			if ( in_array( $page, array( '03', '04', '05', '06', '07', '08', '09', '10', '13', '14', '15', '16' ), true ) && is_file( $image_path ) ) {
				$image_url = add_query_arg( 'ver', filemtime( $image_path ), $image_url );
			}
		} else {
			$uploads   = wp_get_upload_dir();
			$image_url = trailingslashit( $uploads['baseurl'] ) . ltrim( $image['file'], '/' );
		}

		$script_path = get_stylesheet_directory() . '/functions/transport/bus-guide-modal.js';

		wp_enqueue_script(
			'yeg-bus-guide-modal',
			get_stylesheet_directory_uri() . '/functions/transport/bus-guide-modal.js',
			array(),
			file_exists( $script_path ) ? filemtime( $script_path ) : null,
			true
		);
	}

	ob_start();
	?>
	<section class="yeg-bus-guide"<?php echo $show_title ? ' aria-labelledby="' . esc_attr( $title_id ) . '"' : ' aria-label="' . esc_attr__( '交通案内', 'gaia_tcd102_child' ) . '"'; ?>>
		<?php if ( $show_title ) : ?>
			<h2 id="<?php echo esc_attr( $title_id ); ?>" class="yeg-bus-guide__title yeg-event-detail__title"><?php esc_html_e( '交通案内', 'gaia_tcd102_child' ); ?></h2>
		<?php endif; ?>

		<?php if ( $is_international_exchange ) : ?>
			<div class="yeg-bus-guide__stay-notice" role="note">
				<span class="yeg-bus-guide__stay-icon"><?php echo yeg_bus_guide_icon( 'pin' ); ?></span>
				<div>
					<h3 class="yeg-bus-guide__stay-title">国際交流研究会へのバス移動はありません</h3>
					<p>国際交流研究会は<strong>JTAドーム</strong>で開催します。すでにお集まりのJTAドームで、そのままご参加いただけます。</p>
					<p class="yeg-bus-guide__stay-after"><strong>大懇親会への移動：</strong>各研究会会場から専用バスで移動</p>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( ! $is_international_exchange && ! empty( $guide['routes'] ) ) : ?>
			<div class="yeg-bus-guide__panel">
			<header class="yeg-bus-guide__masthead">
				<div class="yeg-bus-guide__masthead-icon"><?php echo yeg_bus_guide_icon( 'bus' ); ?></div>
				<div class="yeg-bus-guide__masthead-content">
					<p class="yeg-bus-guide__page-label"><?php echo esc_html( $guide['page_label'] ); ?></p>
					<p class="yeg-bus-guide__event"><?php echo esc_html( $guide['event'] ); ?></p>
					<h3 class="yeg-bus-guide__direction"><?php echo esc_html( $guide['direction'] ); ?></h3>
				</div>
			</header>

			<div class="yeg-bus-guide__body">
				<?php if ( '14' === $page && '' !== $notice ) : ?>
					<div class="yeg-bus-guide__note" role="note">
						<span class="yeg-bus-guide__note-icon"><?php echo yeg_bus_guide_icon( 'info' ); ?></span>
						<p><?php echo esc_html( $notice ); ?></p>
					</div>
				<?php endif; ?>
				<div class="yeg-bus-guide__routes">
					<?php foreach ( $guide['routes'] as $route ) : ?>
						<?php
						$route_title_id    = $title_id . '-route-' . $route['number'];
						$route_destination = ! empty( $route['destination_name'] ) ? $route['destination_name'] : $guide['destination'];
						$show_service      = ! empty( $route['show_service'] );
						?>
						<section class="yeg-bus-guide__route yeg-bus-guide__route--<?php echo esc_attr( sanitize_html_class( $route['theme'] ) ); ?>" aria-labelledby="<?php echo esc_attr( $route_title_id ); ?>">
							<header class="yeg-bus-guide__route-summary">
								<p class="yeg-bus-guide__route-number"><span>Route</span> <?php echo esc_html( $route['number'] ); ?></p>
								<div class="yeg-bus-guide__route-points">
									<div class="yeg-bus-guide__route-point">
										<p class="yeg-bus-guide__route-point-label"><?php esc_html_e( 'バス停', 'gaia_tcd102_child' ); ?></p>
										<h4 id="<?php echo esc_attr( $route_title_id ); ?>" class="yeg-bus-guide__route-title"><?php echo yeg_bus_boarding_point_link( $route['departure_name'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h4>
									</div>
									<span class="yeg-bus-guide__route-points-arrow" aria-hidden="true">→</span>
									<div class="yeg-bus-guide__route-point">
										<p class="yeg-bus-guide__route-point-label"><?php esc_html_e( '目的地', 'gaia_tcd102_child' ); ?></p>
										<p class="yeg-bus-guide__route-destination"><?php echo esc_html( $route_destination ); ?></p>
									</div>
								</div>
							</header>

							<div class="yeg-bus-guide__route-detail">
								<p class="yeg-bus-guide__scroll-hint"><?php esc_html_e( '時刻表は横にスクロールできます', 'gaia_tcd102_child' ); ?></p>
								<div class="yeg-bus-guide__table-wrap" tabindex="0" role="region" aria-label="<?php echo esc_attr( $route['departure_name'] . 'から' . $route_destination . 'までの運行時刻表' ); ?>">
									<table class="yeg-bus-guide__table">
										<caption class="yeg-bus-guide__caption"><?php echo esc_html( $route['departure_name'] . 'から' . $route_destination . 'までの運行時刻' ); ?></caption>
										<thead>
											<tr>
												<?php if ( $show_service ) : ?>
													<th scope="col"><?php esc_html_e( '便', 'gaia_tcd102_child' ); ?></th>
												<?php endif; ?>
												<th scope="col"><?php esc_html_e( '号車', 'gaia_tcd102_child' ); ?></th>
												<th scope="col"><?php echo esc_html( '出発（' . $route['departure_name'] . '）' ); ?></th>
												<th scope="col"><?php echo esc_html( '到着（' . $route_destination . '）' ); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ( $route['times'] as $time ) : ?>
												<tr>
													<?php if ( $show_service ) : ?>
														<td class="yeg-bus-guide__service"><?php echo esc_html( $time['service'] ); ?></td>
													<?php endif; ?>
													<th scope="row"><?php echo esc_html( $time['buses'] ); ?></th>
													<td><time datetime="<?php echo esc_attr( str_pad( $time['departure'], 5, '0', STR_PAD_LEFT ) ); ?>"><?php echo esc_html( $time['departure'] ); ?></time></td>
													<td><time datetime="<?php echo esc_attr( str_pad( $time['arrival'], 5, '0', STR_PAD_LEFT ) ); ?>"><?php echo esc_html( $time['arrival'] ); ?></time></td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							</div>
						</section>
					<?php endforeach; ?>
				</div>

				<?php if ( ! empty( $guide['follow_up'] ) && is_array( $guide['follow_up'] ) ) : ?>
					<div class="yeg-bus-guide__follow-up" role="note">
						<span class="yeg-bus-guide__follow-up-icon"><?php echo yeg_bus_guide_icon( 'pin' ); ?></span>
						<div>
							<p class="yeg-bus-guide__follow-up-label"><?php echo esc_html( $guide['follow_up']['label'] ); ?></p>
							<p><?php echo esc_html( $guide['follow_up']['text'] ); ?></p>
						</div>
					</div>
				<?php endif; ?>

				<div class="yeg-bus-guide__note" role="note">
					<span class="yeg-bus-guide__note-icon"><?php echo yeg_bus_guide_icon( 'info' ); ?></span>
					<p>※<?php echo esc_html( $guide['note'] ); ?></p>
				</div>
			</div>
			</div>
		<?php elseif ( ! $is_international_exchange && '' !== $notice ) : ?>
			<div class="yeg-bus-guide__note" role="note">
				<span class="yeg-bus-guide__note-icon"><?php echo yeg_bus_guide_icon( 'info' ); ?></span>
				<p><?php echo esc_html( $notice ); ?></p>
			</div>
		<?php endif; ?>
		<?php if ( $image ) : ?>
			<div class="access_img">
				<button class="yeg-bus-guide__image-trigger" type="button" aria-controls="<?php echo esc_attr( $modal_id ); ?>" aria-haspopup="dialog" data-yeg-bus-modal-open>
					<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>">
					<?php if ( $image_badge ) : ?>
						<span class="yeg-bus-guide__image-page-badge" aria-hidden="true"><?php echo esc_html( $image_badge ); ?></span>
					<?php endif; ?>
					<span class="yeg-bus-guide__image-hint"><?php esc_html_e( 'クリックで拡大', 'gaia_tcd102_child' ); ?></span>
				</button>
			</div>
			<dialog id="<?php echo esc_attr( $modal_id ); ?>" class="yeg-bus-guide-modal" role="dialog" aria-label="<?php echo esc_attr( $image['dialog_label'] ); ?>" aria-modal="true" data-yeg-bus-modal>
				<div class="yeg-bus-guide-modal__inner">
					<div class="yeg-bus-guide-modal__image-frame">
						<button class="yeg-bus-guide-modal__close" type="button" aria-label="<?php esc_attr_e( '拡大画像を閉じる', 'gaia_tcd102_child' ); ?>" data-yeg-bus-modal-close>
							<span aria-hidden="true">×</span>
						</button>
						<img class="yeg-bus-guide-modal__image" src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>">
						<?php if ( $image_badge ) : ?>
							<span class="yeg-bus-guide__image-page-badge" aria-hidden="true"><?php echo esc_html( $image_badge ); ?></span>
						<?php endif; ?>
					</div>
				</div>
			</dialog>
		<?php endif; ?>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'yeg_bus_guide', 'yeg_bus_guide_shortcode' );

/**
 * Append the matching bus guide to designated event details.
 *
 * @param string $content Post content.
 * @return string
 */
function yeg_append_event_bus_guide( $content ) {
	if ( ! is_singular( 'events' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}

	$guide_pages = array(
		'general-assembly-20261113'                => '01',
		'opening-ceremony-20261113'                => '02',
		'current-president-training-20261113'      => '03',
		'day1-training-j-international-exchange-20261113' => '03',
		'secretariat-training-20261113'            => '04',
		'day1-training-c-member-growth-20261113'   => '05',
		'ob-og-program-20261113'                   => '05',
		'day1-training-b-pr-dx-20261113'           => '06',
		'day1-training-f-economic-zone-20261113'   => '06',
		'day1-training-e-policy-20261113'          => '07',
		'day1-training-d-vision-20261113'          => '08',
		'day1-training-g-business-co-creation-20261113' => '08',
		'day1-training-i-international-business-20261113' => '08',
		'day1-training-h-startup-20261113'         => '09',
		'day1-training-a-keynote-20261113'         => '10',
		'next-president-training-20261114'          => '11',
		'vice-president-training-20261114'          => '12',
		'executive-director-training-20261114'       => '13',
		'three-major-projects-training-20261114'     => '14',
		'breakout-d-20261114'                         => '15',
		'breakout-e-20261114'                         => '15',
		'breakout-i-20261114'                         => '15',
		'breakout-f-20261114'                         => '16',
		'breakout-g-20261114'                         => '16',
		'breakout-h-20261114'                         => '16',
		'breakout-j-20261114'                         => '17',
		'closing-ceremony-20261114'                   => '18',
	);
	$guide_routes = array(
		'day1-training-c-member-growth-20261113'          => '01,02',
		'day1-training-f-economic-zone-20261113'          => '01,02',
		'day1-training-d-vision-20261113'                => '01,04',
		'day1-training-g-business-co-creation-20261113'  => '03,06',
		'day1-training-i-international-business-20261113' => '02,05',
		'breakout-d-20261114'                            => '01',
		'breakout-e-20261114'                            => '02,04',
		'breakout-i-20261114'                            => '03,05',
		'breakout-f-20261114'                            => '01,04,05',
		'breakout-g-20261114'                            => '02,06',
		'breakout-h-20261114'                            => '03,07',
	);
	$guide_directions = array(
		'breakout-d-20261114' => '往路：研修会場へ輸送',
		'day1-training-c-member-growth-20261113' => '往路・研修終了後：各会場へ輸送',
		'day1-training-f-economic-zone-20261113' => '往路・研修終了後：各会場へ輸送',
	);
	$post_slug = get_post_field( 'post_name', get_the_ID() );

	if ( ! isset( $guide_pages[ $post_slug ] ) ) {
		return $content;
	}

	if ( has_shortcode( $content, 'yeg_bus_guide' ) ) {
		return $content;
	}

	$shortcode = '[yeg_bus_guide page="' . $guide_pages[ $post_slug ] . '"';

	if ( isset( $guide_routes[ $post_slug ] ) ) {
		$shortcode .= ' route="' . $guide_routes[ $post_slug ] . '"';
	}

	if ( isset( $guide_directions[ $post_slug ] ) ) {
		$shortcode .= ' direction="' . esc_attr( $guide_directions[ $post_slug ] ) . '"';
	}

	return $content . do_shortcode( $shortcode . ']' );
}
add_filter( 'the_content', 'yeg_append_event_bus_guide' );
