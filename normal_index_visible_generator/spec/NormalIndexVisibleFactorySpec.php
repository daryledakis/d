<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use NormalIndexVisibleFactoryMySQLDriver;

class NormalIndexVisibleFactorySpec extends ObjectBehavior
{
    function let() {
        $MySqlSettings = array(
            'server' => 'localhost',
            'username' => 'site',
            'password' => 'ajnin_edoc_12',
            'database' => 'site'
        );

        $MySqlDriver = new NormalIndexVisibleFactoryMySQLDriver($MySqlSettings);
        $MySqlDriver->connect();
        $createTable = "CREATE TABLE IF NOT EXISTS `normal_index_visible_test` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `n_id` int(11) NOT NULL,
        `n_sample` varchar(255) DEFAULT NULL,
        `n_url` varchar(255) DEFAULT NULL,
        `is_consignee` int(11) DEFAULT NULL,
        `is_shipper` int(11) DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $MySqlDriver->execute($createTable);

        $this->beConstructedWith($MySqlDriver);
    }


    function letgo() {
        $MySqlSettings = array(
            'server' => 'localhost',
            'username' => 'site',
            'password' => 'ajnin_edoc_12',
            'database' => 'site'
        );
        $MySqlDriver = new NormalIndexVisibleFactoryMySQLDriver($MySqlSettings);
        $MySqlDriver->connect();
        $dropTable = "DROP TABLE IF EXISTS `normal_index_visible_test`; ";
        $MySqlDriver->execute($dropTable);
    }


    function it_is_initializable()
    {
        $this->shouldHaveType('NormalIndexVisibleFactory');
    }


    function it_should_accept_factory_driver() {
        $MySqlSettings = array(
            'server' => 'localhost',
            'username' => 'site',
            'password' => 'ajnin_edoc_12',
            'database' => 'igdata'
        );
        $MySqlDriver = new NormalIndexVisibleFactoryMySQLDriver($MySqlSettings);

        $this->addDriver($MySqlDriver)->shouldReturnAnInstanceOf('NormalIndexVisibleFactory');
    }


    function it_should_accept_table_name() {
        $this->setTableName('normal_index_visible_test')->shouldReturnAnInstanceOf('NormalIndexVisibleFactory');
    }


    function it_should_truncate_the_table() {
        $this->setTableName('normal_index_visible_test');
        $this->truncate()->shouldBeBool();
    }


    function it_should_save_records() {
        $data = array();
        $data[] = array('n_id' => '1639392','n_sample' => 'MSK HUNGARY BT','n_url' => 'msk-hungary-bt','is_consignee' => '0','is_shipper' => '5');
        $data[] = array('n_id' => '2298714','n_sample' => 'NINGBO JUEXI IMPORT AND EXPORT CO.','n_url' => 'ningbo-juexi-import-and-export-co','is_consignee' => '0','is_shipper' => '2');
        $data[] = array('n_id' => '522915','n_sample' => 'METRON GMBH C/O','n_url' => 'metron-gmbh-c-o','is_consignee' => '1','is_shipper' => '4');
        $data[] = array('n_id' => '2181973','n_sample' => 'LIAOCHENG DETONG AUTO PARTS MANUFA','n_url' => 'liaocheng-detong-auto-parts-manufa','is_consignee' => '0','is_shipper' => '1');
        $data[] = array('n_id' => '4623743','n_sample' => 'PLASTYROBEL S.A.S.U.','n_url' => 'plastyrobel-s-a-s-u','is_consignee' => '0','is_shipper' => '1');
        $data[] = array('n_id' => '1148363','n_sample' => 'POLYRACK ELECTRONIC-AUFBAUSYSTEME','n_url' => 'polyrack-electronic-aufbausysteme','is_consignee' => '1','is_shipper' => '0');
        $data[] = array('n_id' => '1439206','n_sample' => 'MAPISA SA CTRA VILLAFAMES-CASTELLON','n_url' => 'mapisa-sa-ctra-villafames-castellon','is_consignee' => '0','is_shipper' => '1');
        $data[] = array('n_id' => '4583057','n_sample' => 'PT. HANKYU HANSHIN EXPRESS INDONESI','n_url' => 'pt-hankyu-hanshin-express-indonesi','is_consignee' => '0','is_shipper' => '1');
        $data[] = array('n_id' => '165217','n_sample' => 'ISRAEL COHEN AND SONS INTL.','n_url' => 'israel-cohen-and-sons-intl','is_consignee' => '3','is_shipper' => '1');
        $data[] = array('n_id' => '2341192','n_sample' => 'CHINA RESOURCES POLYESTER','n_url' => 'china-resources-polyester','is_consignee' => '0','is_shipper' => '1');
        $data[] = array('n_id' => '2404632','n_sample' => 'ALBINI & PITTGLIANI SVERIGA AB','n_url' => 'albini-pittgliani-sveriga-ab','is_consignee' => '0','is_shipper' => '1');
        $data[] = array('n_id' => '2426157','n_sample' => 'ANITA LAZARUS','n_url' => 'anita-lazarus','is_consignee' => '0','is_shipper' => '1');
        $data[] = array('n_id' => '1170906','n_sample' => 'ANTHONY TROTTMAN','n_url' => 'anthony-trottman','is_consignee' => '1','is_shipper' => '0');
        $data[] = array('n_id' => '1623424','n_sample' => 'ZHEJIANG SHENDA IMP. EXP.','n_url' => 'zhejiang-shenda-imp-exp','is_consignee' => '0','is_shipper' => '1');
        $data[] = array('n_id' => '4605189','n_sample' => 'GAMUT ENTERPRISES','n_url' => 'gamut-enterprises','is_consignee' => '0','is_shipper' => '2');
        $data[] = array('n_id' => '1176029','n_sample' => 'PRAIM SEEPERAD','n_url' => 'praim-seeperad','is_consignee' => '1','is_shipper' => '0');
        $data[] = array('n_id' => '3942406','n_sample' => 'COLOUR PAPER TRADING CO.,LIMITED','n_url' => 'colour-paper-trading-co-limited','is_consignee' => '0','is_shipper' => '4');
        $data[] = array('n_id' => '2267140','n_sample' => 'HANGZHOU TIMES LIGHT & ELECTRIC APP','n_url' => 'hangzhou-times-light-electric-app','is_consignee' => '0','is_shipper' => '1');
        $data[] = array('n_id' => '460385','n_sample' => 'SARCOMINC.','n_url' => 'sarcominc','is_consignee' => '1','is_shipper' => '0');
        $this->setTableName('normal_index_visible_test');
        foreach($data as $row) {
            $this->insert($row)->shouldBeInteger();
        }
    }
}
