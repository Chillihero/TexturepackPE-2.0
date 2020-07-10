<?php
class TexturePacksPE {
    /**
     * Returns current dataPath excluding @link TexturePacksPE class
     * @var string|string[]
     */
    public static $dataPath = "";

    /**
     * MySQL Connection to the server. instance of @link MySQLProvider
     * @var MySQLProvider|null $mysql
     */
    public $mysql = null;

    /**
     * JSON parsed $configuration File
     * @var $configuration
     */
    private $configuration = null;

    public $packList = [];

    public function __construct()
    {
        if ($this->mysql === null) $this->mysql = new MySQLProvider("0.0.0.0", "chillihero_db1", "V5Ye*783mo_Q", "chillihero_db1");
        return;
    }
    /**
     * @param string $user
     *
     * @return TexturePack[]
     */
    public function getTexturePacksByPlayer(string $user) :array
    {
        $final_packs = [];
        foreach ($this->mysql->getTexturePacks() as $pack) {
            if ($pack->getAuthor() === $user) $final_packs[] = $pack;
        }
        return $final_packs;
    }

    /**
     * @param int $site
     *
     * @param array|null $users
     * @return User[]
     */
    public function getUsersSite(int $site, array $users = null) :array
    {
        $users = $users ?? $this->mysql->getUsers();
        $sites = array_chunk($users, 10);
        if (isset($sites[$site])) return $sites[$site];
        return $sites[0];
    }
}