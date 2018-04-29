<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 24.4.2018 г.
 * Time: 16:07
 */

namespace Model;


class User {

    private $f_name;
    private $l_name;
    private $email;
    private $password;
    private $id;
    private $city;
    private $neighborhood;
    private $blok;
    private $entrance;

    public function __construct($email = null, $password = null, $f_name = null, $l_name = null, $id = null, $city = null,
                                $neighborhood = null, $blok = null, $entrance = null)
    {

        $this->email = $email;
        $this->password = $password;
        $this->id = $id;
        $this->f_name = $f_name;
        $this->l_name = $l_name;
        $this->city = $city;
        $this->neighborhood = $neighborhood;
        $this->blok = $blok;
        $this->entrance = $entrance;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getFirstName() {
        return $this->f_name;
    }

    public function setFirstName($f_name) {
        $this->f_name = $f_name;
    }

    public function getLastName() {
        return $this->l_name;
    }

    public function setLastName($l_name) {
        $this->l_name = $l_name;
    }

    public function getCity() {
        return $this->city;
    }

    public function setCity($city) {
        $this->city = $city;
    }

    public function getNeighborhood() {
        return $this->neighborhood;
    }

    public function setNeighborhood($neighborhood) {
        $this->neighborhood = $neighborhood;
    }

    public function getBlok() {
        return $this->blok;
    }

    public function setBlok($blok) {
        $this->blok = $blok;
    }

    public function getEntrance() {
        return $this->entrance;
    }

    public function setEntrance($entrance) {
        $this->entrance = $entrance;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }
}



