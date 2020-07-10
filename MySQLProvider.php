<?php
class MySQLProvider {
    /** @var false|mysqli  */
    private $mysql = null;

    /** @var $conndata */
    private $connData;

    /**
     * MySQLProvider constructor.
     *
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $database
     */
    public function __construct(string $host, string $username, string $password, string $database)
    {
        if ($this->mysql !== null) {
            $this->checkValid();
            return;
        }
        $this->mysql = mysqli_connect($host, $username, $password, $database);
        if (!$this->mysql or $this->mysql->connect_error) exit($this->mysql->connect_error);
        // setting some data stuff for reconnect
        $this->connData["host"] = $host;
        $this->connData["username"] = $username;
        $this->connData["password"] = $password;
        $this->connData["database"] = $database;
        return;
    }

    /**
     * Checks if MySQL @link mysql is valid or not (-> timeout checker + reconnect)
     */
    public function checkValid()
    {
        $this->mysql->ping();
        if (!$this->mysql) {
            $this->mysql->close();
            $this->mysql = mysqli_connect($this->connData["host"], $this->connData["username"], $this->connData["password"], $this->connData["database"]);
        }
    }

    /**
     * Checks whether an username is already used or not
     *
     * @api
     *
     * @param string $username
     *
     * @return bool
     */
    public function accountExists(string $username) :bool
    {
        $this->checkValid();
        $stmt = $this->mysql->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $result = $stmt->execute();
        if ($result === false) return false;
        $result = $stmt->get_result();
        if ($result === false) return false;
        if ($result->num_rows === 0) return false;
        return true;
    }

    /**
     * Get account data of a username including username, password (HASHED), created seconds (since 1.1.1970 0:00 GMT -> time() ), Email address
     *
     * @api
     *
     * @param string $username
     *
     * @return User|null
     */
    public function getAccountData(string $username) :?User
    {
        $this->checkValid();
        $stmt = $this->mysql->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $result = $stmt->execute();
        if ($result === false) return null;
        $result = $stmt->get_result();
        if ($result === false) return null;
        if ($result->num_rows === 0) return null;
        if ($val = $result->fetch_array()) {
            $user = new User($username, $val[1], $val[2], $val[3], $this->getRank($val[4]));
            return $user;
        }
        return null;
    }

    public function setAccountData(User $user) {
        $this->checkValid();
        $username = $user->getName();
        $password = $user->getPassword();
        $mail = $user->getMailAddress();
        $createTime = $user->getCreateTime();
        $rank = $user->getRank()->getName();
        $stmt = $this->mysql->prepare("UPDATE users SET password = ?, email = ?, createTime = ?, rank = ? WHERE username = ?");
        $stmt->bind_param("ssiss", $password, $mail, $createTime, $rank, $username);
        $stmt->execute();
    }

    /**
     * Create a new Account for an User
     *
     * @api
     *
     * @param User $user
     *
     * @return bool
     */
    public function createAccount(User $user) :bool
    {
        $this->checkValid();
        /* Need to use vars at $stmt->bind_param */
        $username = $user->getName();
        $password = $user->getPassword();
        $email = $user->getMailAddress();
        $createTime = $user->getCreateTime();
        $rank = $user->getRank()->getName();
        $stmt = $this->mysql->prepare("INSERT INTO users(username, password, email, createTime, rank) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $username, $password, $email, $createTime, $rank);
        $result = $stmt->execute();
        if ($result === false) return false;
        return true;
    }

    /**
     * Returns bool : mail address is already used or not
     *
     * @api
     *
     * @param string $email
     *
     * @return bool
     */
    public function mailExists(string $email) :bool
    {
        $this->checkValid();
        $stmt = $this->mysql->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $result = $stmt->execute();
        if ($result === false) return false;
        $result = $stmt->get_result();
        if ($result === false) return false;
        if ($result->num_rows === 0) return false;
        return true;
    }

    /**
     * Delete an already existing account! Excluding his texture packs
     * Notice: No check
     *
     * @api
     *
     * @param string $username
     */
    public function deleteAccount(string $username)
    {
        $this->checkValid();
        $stmt = $this->mysql->prepare("DELETE FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
    }

    /**
     * @return User[]
     */
    public function getUsers() :array
    {
        $this->checkValid();
        $stmt = $this->mysql->prepare("SELECT * FROM users");
        $result = $stmt->execute();
        if ($result === false) return [];
        $result = $stmt->get_result();
        if ($result === false) return [];
        if ($result->num_rows === 0) return [];
        if ($val = $result->fetch_all()) {
            $user = [];
            foreach ($val as $item) {
                if (!isset($item[4])) continue;
                if (!isset($item[4])) continue;
                $user[] = new User($item[0], $item[1], $item[2], $item[3], $this->getRank($item[4]));
            }
            return $user;
        }
        return [];
    }
    public function isAdmin(string $user) :bool
    {
        $this->checkValid();
        $stmt = $this->mysql->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->bind_param("s", $user);
        $result = $stmt->execute();
        if ($result === false) return false;
        $result = $stmt->get_result();
        if ($result === false) return false;
        if ($result->num_rows === 0) return false;
        return true;
    }

    public function createTexturePack(TexturePack $pack) {
        $this->checkValid();
        $uploader = $pack->getAuthor();
        $creator = $pack->getCreator();
        $link = $pack->getLink();
        $image = $pack->getImage();
        $id = $pack->getId();
        $name = $pack->getName();
        $stmt = $this->mysql->prepare("INSERT INTO texturepacks (`name`, uploader, creator, link, image, id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $name, $uploader, $creator, $link, $image, $id);
        $stmt->execute();
    }
    public function reuploadTexturePack(TexturePack $pack) {
        $this->checkValid();
        $uploader = $pack->getAuthor();
        $creator = $pack->getCreator();
        $link = $pack->getLink();
        $image = $pack->getImage();
        $id = $pack->getId();
        $name = $pack->getName();
        $stmt = $this->mysql->prepare("UPDATE texturepacks SET `name` = ?, uploader = ?, creator = ?, link = ?, image = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $name, $uploader, $creator, $link, $image, $id);
        $stmt->execute();
    }
    public function deleteTexturePack(TexturePack $pack) {
        $id = $pack->getId();
        $stmt = $this->mysql->prepare("DELETE FROM texturepacks WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
    public function getTexturePackById(int $id) :?TexturePack {
        $stmt = $this->mysql->prepare("SELECT * FROM texturepacks WHERE id = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        if ($result === false) return null;
        $result = $stmt->get_result();
        if ($result === false) return null;
        if ($result->num_rows === 0) return null;
        if ($val = $result->fetch_array()) {
            return new TexturePack($val[0], $val[1], $val[2], $val[3], $val[4], $id);
        }
        return null;
    }


    /**
     * Returns a list of all TexturePacks. Array format
     * WIP
     *
     * @api
     *
     * @return TexturePack[]
     */
    public function getTexturePacks() :array
    {
        $this->checkValid();
        $stmt = $this->mysql->prepare("SELECT * FROM texturepacks");
        $result = $stmt->execute();
        if ($result === false) return [];
        $result = $stmt->get_result();
        if ($result === false) return [];
        if ($result->num_rows === 0) return [];
        if ($val = $result->fetch_all()) {
            $texturePacks = [];
            foreach ($val as $item) {
                if (!isset($item[5])) continue;
                $texturePacks[] = new TexturePack($item[0], $item[1], $item[2], $item[3], $item[4], $item[5]);
            }
            return $texturePacks;
        }
        return [];
    }

    public function saveRank(Rank $rank) {
        $this->checkValid();
        $permissions = implode(',', $rank->getPermissions());
        $name = $rank->getName();
        $color = $rank->getColor();
        $stmt = $this->mysql->prepare("UPDATE ranks SET permissions = ?, color = ? WHERE name = ?");
        $stmt->bind_param("sss", $permissions, $color, $name);
        $stmt->execute();
    }
    public function getRank(string $rankname) :Rank
    {
        $this->checkValid();
        $stmt = $this->mysql->prepare("SELECT * FROM ranks WHERE name = ?");
        $stmt->bind_param("s", $rankname);
        $result = $stmt->execute();
        if ($result === false) return new Rank("User", [], "#757575");
        $result = $stmt->get_result();
        if ($result === false) return new Rank("User", [], "#757575");
        if ($result->num_rows === 0) return new Rank("User", [], "#757575");
        if ($val = $result->fetch_array()) {
            return new Rank($val[0], explode(',', $val[1]), $val[2]);
        }
        return new Rank("User", [], "#757575");
    }
    public function createRank(Rank $rank)
    {
        $this->checkValid();
        $permissions = implode(',', $rank->getPermissions());
        $name = $rank->getName();
        $color = $rank->getColor();
        $stmt = $this->mysql->prepare("INSERT INTO ranks (`name`, permissions, color) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $permissions, $color);
        $stmt->execute();
    }
    public function deleteRank(string $rankname)
    {
        $this->checkValid();
        $stmt = $this->mysql->prepare("DELETE FROM ranks WHERE `name` = ?");
        $stmt->bind_param("s", $rankname);
        $stmt->execute();
    }

    /**
     * @return Rank[]
     */
    public function getRanks() :array
    {
        var_dump("test");
        $this->checkValid();
        $stmt = $this->mysql->prepare("SELECT * FROM ranks");
        $result = $stmt->execute();
        if ($result === false) return [];
        $result = $stmt->get_result();
        if ($result === false) return [];
        if ($result->num_rows === 0) return [];
        if ($val = $result->fetch_all()) {
            $ranks = [];
            foreach ($val as $rankData) {
                if (!$rankData[2]) continue;
                $ranks[] = new Rank($rankData[0], explode(',', $rankData[1]), $rankData[2]);
            }
            return $ranks;
        }
        return [];
    }
}