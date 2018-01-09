<?php
use Phalcon\Db\Column as Column;
use Phalcon\Db\Index as Index;
use Phalcon\Config\Adapter\Ini;
use Phalcon\Db\Reference as Reference;
use Phalcon\Mvc\Model\Migration;
use \TrackingApi\Application;

class UsersMigration_1 extends Migration
{
    public function up()
    {
        $config = new Ini(dirname(__DIR__) . '\..\..\..\app\config.ini');
        $this->morphTable(
            'users',
            [
                'columns' => [
                    new Column(
                        'id',
                        [
                            'type'          => Column::TYPE_INTEGER,
                            'size'          => 10,
                            'unsigned'      => true,
                            'notNull'       => true,
                            'autoIncrement' => true,
                            'first'         => true,
                        ]
                    ),
                    new Column(
                        'token_id',
                        [
                            'type'    => Column::TYPE_VARCHAR,
                            'size'    => 255,
                            // 'notNull' => true,
                            'after'   => 'id',
                        ]
                    ),
                    new Column(
                        'username',
                        [
                            'type'     => Column::TYPE_VARCHAR,
                            'size'     => 255,
                            'unsigned' => true,
                            'notNull'  => true,
                            'after'    => 'token_id',
                        ]
                    ),
                    new Column(
                        'secret_key',
                        [
                            'type'     => Column::TYPE_VARCHAR,
                            'size'     => 255,
                            'unsigned' => true,
                            'notNull'  => true,
                            'after'    => 'username',
                        ]
                    ),
                    new Column(
                        'created_at',
                        [
                            'type'    => Column::TYPE_DATETIME,
                            'notNull' => true,
                            'after'   => 'expires_at',
                        ]
                    ),
                    new Column(
                        'updated_at',
                        [
                            'type'    => Column::TYPE_DATETIME,
                            'notNull' => true,
                            'after'   => 'created_at',
                        ]
                    ),
                    new Column(
                        'active',
                        [
                            'type'    => Column::TYPE_INTEGER,
                            'size'    => 1,
                            'notNull' => true,
                            'after'   => 'updated_at',
                        ]
                    ),
                ],
                'indexes' => [
                    new Index(
                        'PRIMARY',
                        [
                            'id',
                        ]
                    ),
                ],
                // 'references' => [
                //     new Reference(
                //         'token_id',
                //         [
                //             'referencedSchema'  => $config->database->dbname,
                //             'referencedTable'   => 'tokens',
                //             'columns'           => [
                //                 'token_id',
                //                 Column::TYPE_INTEGER
                //             ],
                //             'referencedColumns' => [
                //                 'id',
                //                 Column::TYPE_INTEGER
                //             ],
                //         ]
                //     ),
                // ],
                'options' => [
                    'TABLE_TYPE'      => 'BASE TABLE',
                    'ENGINE'          => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci',
                ],
            ]
        );
    }
}