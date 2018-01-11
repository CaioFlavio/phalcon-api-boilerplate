<?php
use Phalcon\Db\Column as Column;
use Phalcon\Db\Index as Index;
use Phalcon\Config\Adapter\Ini;
use Phalcon\Db\Reference as Reference;
use Phalcon\Mvc\Model\Migration;

class TokensMigration_2 extends Migration
{
    public function up()
    {
        $config = new Ini(dirname(__DIR__) . '\..\..\..\app\config.ini');
        $this->morphTable(
            'tokens',
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
                        'user_id',
                        [
                            'type'     => Column::TYPE_INTEGER,
                            'size'     => 10,
                            'unsigned' => true,
                            'notNull'  => true,
                            'after'    => 'id',
                        ]
                    ),
                    new Column(
                        'token',
                        [
                            'type'     => Column::TYPE_VARCHAR,
                            'size'     => 255,
                            'unsigned' => true,
                            'notNull'  => true,
                            'after'    => 'secret_key',
                        ]
                    ),
                    new Column(
                        'expires_at',
                        [
                            'type'    => Column::TYPE_DATETIME,
                            'notNull' => true,
                            'after'   => 'token',
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
                'references' => [
                    new Reference(
                        'user_token_fk',
                        [
                            'referencedTable'   => 'users',
                            'columns'           => [
                                'user_id',
                            ],
                            'referencedColumns' => [
                                'id',
                            ],
                        ]
                    ),
                ],
                'options' => [
                    'TABLE_TYPE'      => 'BASE TABLE',
                    'ENGINE'          => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci',
                ],
            ]
        );     
    }
}