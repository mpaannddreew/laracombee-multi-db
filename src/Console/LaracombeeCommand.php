<?php

namespace FannyPack\LaracombeeMultiDB\Console;

use FannyPack\LaracombeeMultiDB\MultiDBFacade;
use Illuminate\Console\Command;
use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Model;

class LaracombeeCommand extends Command
{
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected function db() {
        return $this->option('db');
    }

    /**
     * Add User property.
     *
     * @param string $property.
     * @param string $type.
     *
     * @return \Recombee\RecommApi\Requests\AddUserProperty
     */
    public function addUserProperty(string $property, string $type)
    {
        return MultiDBFacade::db($this->db())->addUserProperty($property, $type);
    }

    /**
     * Add Item property.
     *
     * @param string $property.
     * @param string $type.
     *
     * @return \Recombee\RecommApi\Requests\AddItemProperty
     */
    public function addItemProperty(string $property, string $type)
    {
        return MultiDBFacade::db($this->db())->addItemProperty($property, $type);
    }

    /**
     * Delete User property.
     *
     * @param string $property.
     *
     * @return \Recombee\RecommApi\Requests\DeleteUserProperty
     */
    public function deleteUserProperty(string $property)
    {
        return MultiDBFacade::db($this->db())->deleteUserProperty($property);
    }

    /**
     * Delete Item property.
     *
     * @param string $property.
     *
     * @return \Recombee\RecommApi\Requests\DeleteItemProperty
     */
    public function deleteItemProperty(string $property)
    {
        return MultiDBFacade::db($this->db())->deleteItemProperty($property);
    }

    /**
     * Add user to recombee.
     *
     * @param \Illuminate\Foundation\Auth\User $user.
     *
     * @return \Recombee\RecommApi\Requests\Request
     */
    public function addUser(User $user)
    {
        return MultiDBFacade::db($this->db())->addUser($user);
    }

    /**
     * Add item to recombee.
     *
     * @param \Illuminate\Database\Eloquent\Model $item.
     *
     * @return \Recombee\RecommApi\Requests\Request
     */
    public function addItem(Model $item)
    {
        return MultiDBFacade::db($this->db())->addItem($item);
    }

    /**
     * Add users as bulk.
     *
     * @param array $batch.
     *
     * @return \Recombee\RecommApi\Requests\Request
     */
    public function addUsers(array $batch)
    {
        return MultiDBFacade::db($this->db())->addUsers($batch);
    }

    /**
     * Add items as bulk.
     *
     * @param array $batch.
     *
     * @return \Recombee\RecommApi\Requests\Request
     */
    public function addItems(array $batch)
    {
        return MultiDBFacade::db($this->db())->addItems($batch);
    }
}
