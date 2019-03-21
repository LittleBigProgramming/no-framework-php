<?php

namespace App\Models;

/**
 * @Entity @Table(name="users")
 *
 */
class User
{
    /**
     * @GeneratedValue(strategy="AUTO")
     * @Id @Column(name="id", type="integer", nullable=false)
     */
    protected $id;

    /**
     * @name @Column(type="string")
     */
    protected $name;

    /**
     * @email @Column(type="string")
     */
    protected $email;

    /**
     * @password @Column(type="string")
     */
    protected $password;

    /**
     * @remember_token @Column(type="string")
     */
    protected $remember_token;

    /**
     * @remember_identifier @Column(type="string")
     */
    protected $remember_identifier;
}
