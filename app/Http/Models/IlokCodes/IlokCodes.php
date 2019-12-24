<?php

namespace App\Http\Models\IlokCodes;

use App\Http\Models\Helper\Helper;
use Illuminate\Database\Eloquent\Model;

class IlokCodes extends Model
{
    protected $table = 'ilokcodes';
    protected $fillable = ['used'];

    /**
     * Get IlokCodes
     * @param   array  $per_page
     * @return collection
     */
    public static function getListIlokCodes($per_page){
        $ilokcodes =  IlokCodes::select('products.code','products.name','ilokcodes.id', 'ilokcodes.ilok_code','ilokcodes.batchtime','ilokcodes.used')
            ->leftjoin('products','products.id','ilokcodes.product_id')
            ->groupby('ilokcodes.id')
            ->paginate($per_page);
        return $ilokcodes;
    }
    /**
     * Remove IlokCodes
     * @param   array  $ids
     * @return bool
     */
    public static function remove($ids){

        IlokCodes::whereIn('id',$ids)->delete();
        return true;
    }
    /**
     * Insert IlokCodes
     * @param   array  $data
     * @return bool
     */
    public static function import($data){
        IlokCodes::insert($data);
        return true;
    }

    //FRONT
    /**
     * Gets the next free code from the iLok code table
     * @return bool|string   The iLok code, false on failure
     */
    public static function getFreeCode($productID)
    {


        $result = IlokCodes::leftjoin('products as p','p.id','ilokcodes.product_id')
            ->where('ilokcodes.used','<>','1')
            ->where('p.id',$productID)
            ->orderBy('ilokcodes.id')->get()->toArray();

        /*  $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('ic.ilok_code,p.name')->from('#__jappactivation_ilokcodes AS ic');
        $query->leftJoin('#__jappactivation_products AS p ON p.id = ic.product_id');

        $query->where('ic.used != ' . $db->quote(1));
        $query->where('p.id = ' . $db->quote($productID));
        $query->order('ic.id');

        $db->setQuery($query);
        */

       /* try {
            $result = $db->loadObjectList();
        } catch (RuntimeException $e) {
            return false;
        }
        */

        // Get count of unused
        if (count($result) > 0)
        {
            $newCode = $result[0]['ilok_code'];

           // $params = JComponentHelper::getParams('com_jappactivation');
            $lowStockTriggerLevel = env('ILOK_STOCK_WARNING_TRIGGER_LEVEL');
            if (count($result) < intval($lowStockTriggerLevel))
            {
                $productName = $result[0]['name'];
                Helper::sendIlokStockWarning($productName);
            }

            return $newCode;
        }

        return false;
    }
    /**
     * Marks an iLok code as used
     *
     * @param $ilokCode      string      The iLok code
     *
     * @return boolean        true on success, false else
     */
    public static function consumeCode($ilokCode) {

        try
        {
            $result = IlokCodes::where('ilok_code',$ilokCode)->update(['used' => 1]);
            //dd($result);
        }
        catch(RuntimeException $e)
        {
            return false;
        }

        return $result;
    }
}
