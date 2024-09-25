<?php

namespace XoopsModules\Tad_faq;

use XoopsModules\Tadtools\Utility;

/*
Update Class Definition

You may not change or alter any portion of this comment or credits of
supporting developers from this source code or any supporting source code
which is considered copyrighted (c) material of the original comment or credit
authors.

This program is distributed in the hope that it will be useful, but
WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @license      http://www.fsf.org/copyleft/gpl.html GNU public license
 * @copyright    https://xoops.org 2001-2017 &copy; XOOPS Project
 * @author       Mamba <mambax7@gmail.com>
 */

/**
 * Class Update
 */
class Update
{
    public static function chk_chk1()
    {
        global $xoopsDB;
        $sql = 'SELECT count(`counter`) FROM ' . $xoopsDB->prefix('tad_faq_content');
        $result = Utility::query($sql);
        if (empty($result)) {
            return false;
        }

        return true;
    }

    public static function go_update1()
    {
        global $xoopsDB;
        $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tad_faq_content') . ' ADD `counter` SMALLINT(5) NOT NULL';
        Utility::query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        return true;
    }

    //修正uid欄位
    public static function chk_uid()
    {
        global $xoopsDB;
        $sql = "SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS
        WHERE table_name = '" . $xoopsDB->prefix('tad_faq_content') . "' AND COLUMN_NAME = 'uid'";
        $result = Utility::query($sql);
        list($type) = $xoopsDB->fetchRow($result);
        if ('smallint' === $type) {
            return true;
        }

        return false;
    }

    //執行更新
    public static function go_update_uid()
    {
        global $xoopsDB;
        $sql = 'ALTER TABLE `' . $xoopsDB->prefix('tad_faq_content') . '` CHANGE `uid` `uid` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0';
        Utility::query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        return true;
    }

    //刪除錯誤的重複欄位及樣板檔
    public static function chk_tad_faq_block()
    {
        global $xoopsDB;
        //die(var_export($xoopsConfig));
        include XOOPS_ROOT_PATH . '/modules/tad_faq/xoops_version.php';

        //先找出該有的區塊以及對應樣板
        foreach ($modversion['blocks'] as $i => $block) {
            $show_func = $block['show_func'];
            $tpl_file_arr[$show_func] = $block['template'];
            $tpl_desc_arr[$show_func] = $block['description'];
        }

        //找出目前所有的樣板檔
        $sql = 'SELECT bid,show_func,template FROM `' . $xoopsDB->prefix('newblocks') . "`
        WHERE `dirname` = 'tad_faq' ORDER BY `func_num`";
        $result = Utility::query($sql);
        while (list($bid, $show_func, $template) = $xoopsDB->fetchRow($result)) {
            //假如現有的區塊和樣板對不上就刪掉
            if ($template != $tpl_file_arr[$show_func]) {
                $sql = 'DELETE FROM ' . $xoopsDB->prefix('newblocks') . " WHERE bid='{$bid}'";
                Utility::query($sql, 'i', [$bid]) or Utility::web_error($sql, __FILE__, __LINE__);

                //連同樣板以及樣板實體檔案也要刪掉
                $sql = 'DELETE FROM ' . $xoopsDB->prefix('tplfile') . ' AS a
                    LEFT JOIN ' . $xoopsDB->prefix('tplsource') . "  AS b ON a.tpl_id=b.tpl_id
                    WHERE a.tpl_refid=? AND a.tpl_module='tad_faq' AND a.tpl_type='block'";
                Utility::query($sql, 'i', [$bid]) or Utility::web_error($sql, __FILE__, __LINE__);
            } else {
                $sql = 'UPDATE ' . $xoopsDB->prefix('tplfile') . "
                SET `tpl_file`=? , `tpl_desc`=?
                WHERE `tpl_refid`=?";
                Utility::query($sql, 'ssi', [$template, $tpl_desc_arr[$show_func], $bid]) or Utility::web_error($sql, __FILE__, __LINE__);
            }
        }
    }
}
