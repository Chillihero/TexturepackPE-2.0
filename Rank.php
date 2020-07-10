<?php

class Rank {
    /** @var string $name */
    private $name;

    /** @var array $permissions */
    private $permissions;

    /** @var string $color */
    private $color;
    /**
     * Rank constructor.
     * @param string $name
     * @param array $permissions
     * @param string $color
     */
    public function __construct(string $name, array $permissions, string $color)
    {
        $this->name = $name;
        $fperms = [];
        foreach ($permissions as $permission) { if ($permission !== "" and $permission !== " ") $fperms[] = $permission; }
        $this->permissions = $fperms;
        $this->color = $color;
    }

    /**
     * Returns the name of the rank
     * Method to cache rank and sort it
     *
     * @api
     *
     * @return string
     */
    public function getName() :string
    {
        return $this->name;
    }

    /**
     * Returns a list of all permissions for this rank
     * You can look in @link PermissionIds for all valid ids
     *
     * @api
     *
     * @return array
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    /**
     * Returns whether this rank has a permission or not
     * Used for @link Adminpanel.php
     *
     * @api
     *
     * @param string $permission
     *
     * @return bool
     */
    public function hasPermission(string $permission) :bool
    {
        if (in_array("defaults.*", $this->getPermissions())) return true;
        if (in_array($permission, $this->permissions)) return true;
        $f = "";
        for($i = 0; $i < strripos($permission, "."); $i++) {
            $f .= $permission[$i];
        }
        foreach ($this->permissions as $perm) {
            $perm = str_replace(" ", "", $perm);
            if (strrchr($perm, ".") !== ".*") continue;
            $permModified = "";
            for($i = 0; $i < strripos($perm, "."); $i++) {
                $permModified .= $perm[$i];
            }
            if ($permModified === $f) return true;
        }
        return false;
    }

    /**
     * Returns HEX color unit
     * Make sure to set it correctly
     *
     * @api
     *
     * @return string
     */
    public function getColor() :string
    {
        return $this->color;
    }

    public function canEnterAdminPanel() :bool
    {
        return (count($this->getPermissions()) > 0);
    }
}