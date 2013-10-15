<?php
/**
 * The helper for process items fields
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Helpers;

class ItemsFields
{
    /**
     * Create the list for load items
     *
     * @param array $items
     *        the list of items fields (cid => loaded)
     * @param array $fields
     *        required fields
     * @param array $config
     *        the type config
     * @return array
     *         [cids => [1, 2, 3], fields => ['title', 'text']]
     */
    public static function createListForLoad(array $items, array $fields, array $config)
    {
        $cids = array();
        $load = array();
        $fields = \array_flip($fields);
        $cfields = $config['fields'];
        foreach ($items as $cid => $item) {
            $lfields = \array_diff($fields, \array_intersect_key($fields, $item));
            if (!empty($lfields)) {
                $cids[] = $cid;
                foreach ($lfields as $lfield => $v) {
                    if (!isset($cfields[$lfield])) {
                        throw new \go\I18n\Exceptions\ItemsFieldNotExists($lfield);
                    }
                    $load[$cfields[$lfield]] = true;
                }
            }
        }
        return array(
            'cids' => $cids,
            'fields' => \array_keys($load),
        );
    }

    /**
     * Create the list of loaded fields
     *
     * @param array $result
     *        the result of a request to a storage
     * @param array $fields
     *        required fields
     * @param array $cids
     *        the list of required cid
     * @param array $config
     *        the type config
     * @return array
     *         [cid => [loaded list]]
     */
    public static function createLoadedList(array $result, array $cids, array $fields, array $config)
    {
        $cfields = $config['fields'];
        $loaded = array();
        foreach ($cids as $cid) {
            $res = isset($result[$cid]) ? $result[$cid] : array();
            $item = array();
            foreach ($fields as $field) {
                $rfield = $cfields[$field];
                if (isset($res[$rfield])) {
                    $item[$field] = $res[$rfield];
                } else {
                    $item[$field] = '';
                }
            }
            $loaded[$cid] = $item;
        }
        return $loaded;
    }
}
