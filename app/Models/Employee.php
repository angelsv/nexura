<?php

declare(strict_types=1);
namespace App\Models;

class Employee
{
    private int $id;
    private string $nombre;
    private string $email;
    private string $sexo;
    private int $area_id;
    private int $boletin;
    private string $descripcion;
    private array $roles;

    /**
     * Get the value of id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param int $id
     *
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of nombre
     *
     * @return string
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     *
     * @param string $nombre
     *
     * @return self
     */
    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get the value of email
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @param string $email
     *
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of sexo
     *
     * @return string
     */
    public function getSexo(): string
    {
        return $this->sexo;
    }

    /**
     * Set the value of sexo
     *
     * @param string $sexo
     *
     * @return self
     */
    public function setSexo(string $sexo): self
    {
        $this->sexo = $sexo;

        return $this;
    }

    /**
     * Get the value of area_id
     *
     * @return int
     */
    public function getAreaId(): int
    {
        return $this->area_id;
    }

    /**
     * Set the value of area_id
     *
     * @param int $area_id
     *
     * @return self
     */
    public function setAreaId(int $area_id): self
    {
        $this->area_id = $area_id;

        return $this;
    }

    /**
     * Get the value of boletin
     *
     * @return int
     */
    public function getBoletin(): int
    {
        return $this->boletin;
    }

    /**
     * Set the value of boletin
     *
     * @param int $boletin
     *
     * @return self
     */
    public function setBoletin(int $boletin): self
    {
        $this->boletin = $boletin;

        return $this;
    }

    /**
     * Get the value of descripcion
     *
     * @return string
     */
    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    /**
     * Set the value of descripcion
     *
     * @param string $descripcion
     *
     * @return self
     */
    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get the value of roles
     *
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * Set the value of roles
     *
     * @param array $roles
     *
     * @return self
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
}