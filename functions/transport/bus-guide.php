<?php
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
					'departure_name' => 'パイナガマビーチ',
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
					'departure_name' => 'パイナガマビーチ',
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
						array( 'buses' => '18～21号車', 'departure' => '17:45', 'arrival' => '18:05' ),
					),
				),
			),
			'note'        => '交通状況により前後する可能性があります。余裕をもった運行計画を行います。',
		),
		'04' => array(
			'page_label'  => 'Page 04',
			'event'       => '事務局研修 輸送計画',
			'direction'   => '往路：ホテルアトールエメラルド宮古島へ輸送',
			'destination' => 'ホテルアトールエメラルド宮古島',
			'image'       => array(
				'source'       => 'theme',
				'file'         => 'img/transport/page-04-secretariat.png',
				'alt'          => '事務局研修のJTAドーム発ホテルアトールエメラルド宮古島行き輸送計画',
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
						array( 'buses' => '4号車', 'departure' => '13:15', 'arrival' => '13:35' ),
						array( 'buses' => '5号車', 'departure' => '13:20', 'arrival' => '13:40' ),
						array( 'buses' => '6号車', 'departure' => '13:25', 'arrival' => '13:45' ),
					),
				),
			),
			'follow_up'   => array(
				'label' => '研修終了後',
				'text'  => 'ホテルアトールエメラルド宮古島から漲水シンボル緑地へ徒歩で移動してください。',
			),
			'note'        => '交通状況により前後する可能性があります。余裕をもった運行計画を行います。',
		),
		'05' => array(
			'page_label'  => 'Page 05',
			'event'       => 'OB・OG研修＆会員増強研究会（ヒルトン）輸送計画',
			'direction'   => '往路・研修終了後：各会場へ輸送',
			'destination' => 'キャノピー by ヒルトン宮古島',
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
					'destination_name' => 'キャノピー by ヒルトン宮古島',
					'times'            => array(
						array( 'buses' => '7～9号車', 'departure' => '14:35', 'arrival' => '14:55' ),
						array( 'buses' => '10～12号車', 'departure' => '14:40', 'arrival' => '15:00' ),
					),
				),
				array(
					'number'           => '02',
					'theme'            => 'orange',
					'departure_name'   => 'キャノピー by ヒルトン宮古島',
					'destination_name' => '漲水シンボル緑地',
					'times'            => array(
						array( 'buses' => '1～3号車', 'departure' => '18:05', 'arrival' => '18:10' ),
						array( 'buses' => '4～6号車', 'departure' => '18:10', 'arrival' => '18:15' ),
					),
				),
			),
			'note'        => '交通状況により前後する可能性があります。余裕をもった運行計画を行います。',
		),
		'06' => array(
			'page_label'  => 'Page 06',
			'event'       => '広報DX研究会・YEG経済圏構築研究会 輸送計画',
			'direction'   => '往路・研修終了後：各会場へ輸送',
			'destination' => '未来創造センター（研修室）',
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
					'destination_name' => '未来創造センター（研修室）',
					'times'            => array(
						array( 'buses' => '1・2号車', 'departure' => '14:35', 'arrival' => '14:50' ),
						array( 'buses' => '3・4号車', 'departure' => '14:40', 'arrival' => '14:55' ),
						array( 'buses' => '5・6号車', 'departure' => '14:45', 'arrival' => '15:00' ),
					),
				),
				array(
					'number'           => '02',
					'theme'            => 'orange',
					'departure_name'   => '未来創造センター（研修室）',
					'destination_name' => '漲水シンボル緑地',
					'times'            => array(
						array( 'buses' => '1～3号車', 'departure' => '17:35', 'arrival' => '17:45' ),
						array( 'buses' => '4～6号車', 'departure' => '17:40', 'arrival' => '17:50' ),
					),
				),
			),
			'note'        => '交通状況により前後する可能性があります。余裕をもった運行計画を行います。',
		),
		'07' => array(
			'page_label'  => 'Page 07',
			'event'       => '政策提言研究会 輸送計画',
			'direction'   => '往路：ホテルシギラミラージュへ輸送',
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
						array( 'buses' => '28～30号車', 'departure' => '14:40', 'arrival' => '15:00' ),
					),
				),
			),
			'note'        => '交通状況により前後する可能性があります。余裕をもった運行計画を行います。',
		),
		'08' => array(
			'page_label'  => 'Page 08',
			'event'       => '研修D・G・I 共通輸送計画',
			'direction'   => '往路：各研修会場へ輸送',
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
						array( 'buses' => '25号車', 'departure' => '14:40', 'arrival' => '15:00' ),
					),
				),
				array(
					'number'           => '02',
					'theme'            => 'green',
					'departure_name'   => 'JTAドーム',
					'destination_name' => 'ホテルブリーズベイマリーナ',
					'times'            => array(
						array( 'buses' => '20号車', 'departure' => '14:35', 'arrival' => '14:55' ),
						array( 'buses' => '21号車', 'departure' => '14:40', 'arrival' => '15:00' ),
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
						array( 'buses' => '17・18号車', 'departure' => '14:45', 'arrival' => '15:05' ),
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
				'file'         => 'img/transport/page-10-asato.png',
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
						array( 'buses' => '22～25号車', 'departure' => '13:45', 'arrival' => '14:05' ),
						array( 'buses' => '15～18号車', 'departure' => '13:50', 'arrival' => '14:10' ),
						array( 'buses' => '19・28～30号車', 'departure' => '13:55', 'arrival' => '14:15' ),
					),
				),
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
			'notice'     => '現地域のバスはございません、現地集合でお願いします。研修終了後にJTAドーム行きのバスを用意していますので、現地スタッフまでお問い合わせ下さい。',
		),
		'13' => array(
			'page_label' => 'Page 13',
			'event'      => '特別研修B（専務）輸送計画',
			'image'      => array(
				'source'       => 'theme',
				'file'         => 'img/transport/page-13-executive-director.jpg',
				'alt'          => '特別研修B（専務）の輸送計画',
				'dialog_label' => '特別研修B（専務）の輸送計画を拡大表示',
			),
			'notice'     => '現地域のバスはございません、現地集合でお願いします。研修終了後にJTAドーム行きのバスを用意していますので、現地スタッフまでお問い合わせ下さい。',
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
			'notice'     => '現地域のバスはございません、現地集合でお願いします。研修終了後にJTAドーム行きのバスを用意していますので、現地スタッフまでお問い合わせ下さい。',
		),
		'15' => array(
			'page_label'  => 'Page 15',
			'event'       => '分科会D・E・I 共通輸送計画',
			'direction'   => '往路：各分科会会場へ輸送',
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
						array( 'buses' => '22号車', 'departure' => '8:55', 'arrival' => '9:10' ),
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
			),
			'note'        => '交通状況により前後する可能性があります。余裕をもった運行計画を行います。',
		),
		'16' => array(
			'page_label'  => 'Page 16',
			'event'       => '分科会F・G・H 共通輸送計画',
			'direction'   => '往路：各分科会会場へ輸送',
			'destination' => '雪塩ミュージアム・狩俣地区',
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
					'destination_name' => '雪塩ミュージアム・狩俣地区',
					'times'            => array(
						array( 'buses' => '25号車 A班', 'departure' => '8:45', 'arrival' => '9:10' ),
						array( 'buses' => '26号車 B班', 'departure' => '8:45', 'arrival' => '9:10' ),
					),
				),
				array(
					'number'           => '02',
					'theme'            => 'orange',
					'departure_name'   => '各自徒歩',
					'destination_name' => '人頭税石',
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
						array( 'buses' => '27号車', 'departure' => '8:40', 'arrival' => '9:10' ),
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
			'notice'     => '現地域のバスはございません、現地集合でお願いします。研修終了後にJTAドーム行きのバスを用意していますので、現地スタッフまでお問い合わせ下さい。',
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

	$guide           = $registry[ $page ];
	$guide['routes'] = isset( $guide['routes'] ) && is_array( $guide['routes'] ) ? $guide['routes'] : array();
	$route_number    = sanitize_text_field( (string) $atts['route'] );
	$direction       = sanitize_text_field( (string) $atts['direction'] );
	$notice          = isset( $guide['notice'] ) ? (string) $guide['notice'] : '';

	if ( '' !== $direction ) {
		$guide['direction'] = $direction;
	}

	if ( '' !== $route_number ) {
		$guide['routes'] = array_values(
			array_filter(
				$guide['routes'],
				function ( $route ) use ( $route_number ) {
					return isset( $route['number'] ) && $route_number === (string) $route['number'];
				}
			)
		);

		if ( empty( $guide['routes'] ) ) {
			return '';
		}
	}

	$show_title  = ! in_array( strtolower( trim( (string) $atts['show_title'] ) ), array( '0', 'false', 'no', 'off' ), true );
	$title_id    = wp_unique_id( 'yeg-bus-guide-title-' );
	$image       = isset( $guide['image'] ) && is_array( $guide['image'] ) ? $guide['image'] : null;
	$modal_id    = $image ? wp_unique_id( 'yeg-bus-guide-image-modal-' ) : '';
	$image_url   = '';
	$image_badge = $image && ! empty( $image['badge'] ) ? (string) $image['badge'] : '';

	if ( $image ) {
		if ( isset( $image['source'] ) && 'theme' === $image['source'] ) {
			$image_url = trailingslashit( get_stylesheet_directory_uri() ) . ltrim( $image['file'], '/' );
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

		<?php if ( ! empty( $guide['routes'] ) ) : ?>
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
										<h4 id="<?php echo esc_attr( $route_title_id ); ?>" class="yeg-bus-guide__route-title"><?php echo esc_html( $route['departure_name'] ); ?></h4>
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
		<?php elseif ( '' !== $notice ) : ?>
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
	);
	$guide_routes = array(
		'day1-training-c-member-growth-20261113'          => '01',
		'day1-training-f-economic-zone-20261113'          => '01',
		'day1-training-d-vision-20261113'                => '01',
		'day1-training-g-business-co-creation-20261113'  => '03',
		'day1-training-i-international-business-20261113' => '02',
		'breakout-d-20261114'                            => '01',
		'breakout-e-20261114'                            => '02',
		'breakout-i-20261114'                            => '03',
		'breakout-f-20261114'                            => '01',
		'breakout-g-20261114'                            => '02',
		'breakout-h-20261114'                            => '03',
	);
	$guide_directions = array(
		'day1-training-c-member-growth-20261113' => '往路：研修会場へ輸送',
		'day1-training-f-economic-zone-20261113' => '往路：研修会場へ輸送',
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
