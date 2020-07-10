<?php

class PermissionIds {

    const ADMIN = "defaults.*";

    const DELETE_ACCOUNTS = "defaults.delete_accounts";

    const EDIT_TEXTUREPACKS = "defaults.texturepacks.*";

    const EDIT_TEXTUREPACK_NAME = "defaults.texturepacks.rename";

    const DELETE_TEXTUREPACKS = "defaults.texturepacks.delete";

    const GROUP_CHANGE = "defaults.group.change";

    const GROUP_EDIT = "defaults.group.edit";

    const GROUP_CREATE = "defaults.group.create";

    const GROUP_DELETE = "defaults.group.delete";

    const GROUP_MANAGE = "defaults.group.*";

    public static $permissionIds = [
        self::ADMIN,
        self::DELETE_ACCOUNTS,
        self::EDIT_TEXTUREPACK_NAME,
        self::EDIT_TEXTUREPACKS,
        self::DELETE_TEXTUREPACKS,
        self::GROUP_EDIT,
        self::GROUP_CREATE,
        self::GROUP_DELETE,
        self::GROUP_MANAGE
    ];

    public static $stringIds = [
        "admin" => self::ADMIN,
        "delete_accounts" => self::DELETE_ACCOUNTS,
        "edit_texturepack_name" => self::EDIT_TEXTUREPACK_NAME,
        "edit_texturepacks" => self::EDIT_TEXTUREPACKS,
        "delete_texturepack" => self::DELETE_ACCOUNTS,
        "group_change" => self::GROUP_CHANGE,
        "group_edit" => self::GROUP_EDIT,
        "group_create" => self::GROUP_CREATE,
        "group_delete" => self::GROUP_DELETE,
        "group_manage" => self::GROUP_MANAGE
    ];
}