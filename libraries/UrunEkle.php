<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UrunEkle {

    public function __construct()
    {
        // Kütüphane oluşturulduğunda yapılacak işlemler
        $this->CI =& get_instance(); // CodeIgniter örneğini alın
    }

    public function getHTMLResponse($id='')
    {
        // AJAX isteği ile gelen veriyi alın

        // İstenilen HTML kodunu oluşturun
        $html = '<div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label">Ürün İsmi</label>
                        <select class="form-control" required onchange="urunbilgiler(this);" data-id="'.$id.'" id="urun_ismi'.$id.'" name="urun_ismi[]">
                            <option>Seçiniz</option>';
        $urunget = $this->CI->db->get('tblkargolist')->result_array();
        foreach($urunget as $ff){
            $html .= "<option value='".$ff['id']."'>".$ff['urun_ismi']."</option>";
        }
        $html .= '       </select>
                    </div>
                </div>
                <div class="col-sm-1">
    <div class="form-group">

        <label class="control-label">Ürün Adet</label>
        <input type="text" class="form-control" autocomplete="off" value="1" name="urun_adet[]" id="urun_adet'.$id.'">
    </div>


</div><!-- Col -->
<div class="col-sm-1">
    <div class="form-group">

        <label class="control-label">Ürün Stok</label>
        <input type="text" class="form-control" disabled autocomplete="off" name="urun_stokmodal1[]" id="urun_stokmodal1'.$id.'">
        <input type="hidden" name="urun_stokmodal[]" id="urun_stokmodal'.$id.'">
    </div>


</div><!-- Col -->
<div class="col-sm-1">

    <div class="form-group">

        <label class="control-label">Ürün Fiyat</label>
        <input type="text" class="form-control" disabled autocomplete="off" name="urun_fiyatmodal1[]"
            id="urun_fiyatmodal1'.$id.'">
        <input type="hidden" name="urun_fiyatmodal[]" id="urun_fiyatmodal<?=$id?>">
    </div>

</div><!-- Col -->
<div class="col-sm-4">

    <div class="form-group">

        <label class="control-label">Ürün Fotoğrafı</label>

        <img id="urunresimsrc'.$id.'" width="50%" height="50%">

    </div>

</div><!-- Col -->
<div class="col-sm-12">
    <div class="form-group">

        <label class="control-label">Ürün Açıklama</label>
        <input type="text" class="form-control" disabled autocomplete="off" name="urun_aciklamamodal[]"
            id="urun_aciklamamodal'.$id.'">
        <input type="hidden" name="urun_aciklamamodal[]" id="urun_aciklamamodal<?=$id?>">

    </div>

</div><!-- Col -->
                ';

        // HTML kodunu döndürün
        return $html;
    }

}

/*
 ESKİ KOD:
<?php
include "../inc/db.php";
$id=$_POST['urunsayac'];
?>

<div class="col-sm-4">
    <div class="form-group">
        <label class="control-label">Ürün İsmi</label>
        <select class="form-control" required onchange="urunbilgiler(this);" data-id="<?=$id?>" id="urun_ismi<?=$id?>" name="urun_ismi[]">
            <option>Seçiniz</option>
            <?php
                                                                $urunget=$db->prepare("SELECT * FROM tblkargolist");
                                                                $urunget->execute();
                                                                $urunresult=$urunget->fetchAll(PDO::FETCH_ASSOC);
                                                                foreach($urunresult as $ff){
                                                                    echo "<option value='".$ff['id']."'>".$ff['urun_ismi']."</option>";
                                                                }

                                                            ?>
        </select>
    </div>


</div>
<div class="col-sm-1">
    <div class="form-group">

        <label class="control-label">Ürün Adet</label>
        <input type="text" class="form-control" autocomplete="off" value="1" name="urun_adet[]" id="urun_adet<?=$id?>">
    </div>


</div><!-- Col -->
<div class="col-sm-1">
    <div class="form-group">

        <label class="control-label">Ürün Stok</label>
        <input type="text" class="form-control" disabled autocomplete="off" name="urun_stokmodal1[]" id="urun_stokmodal1<?=$id?>">
        <input type="hidden" name="urun_stokmodal[]" id="urun_stokmodal<?=$id?>">
    </div>


</div><!-- Col -->
<div class="col-sm-1">

    <div class="form-group">

        <label class="control-label">Ürün Fiyat</label>
        <input type="text" class="form-control" disabled autocomplete="off" name="urun_fiyatmodal1[]"
            id="urun_fiyatmodal1<?=$id?>">
        <input type="hidden" name="urun_fiyatmodal[]" id="urun_fiyatmodal<?=$id?>">
    </div>

</div><!-- Col -->
<div class="col-sm-4">

    <div class="form-group">

        <label class="control-label">Ürün Fotoğrafı</label>

        <img id="urunresimsrc<?=$id?>" width="50%" height="50%">

    </div>

</div><!-- Col -->
<div class="col-sm-12">
    <div class="form-group">

        <label class="control-label">Ürün Açıklama</label>
        <input type="text" class="form-control" disabled autocomplete="off" name="urun_aciklamamodal[]"
            id="urun_aciklamamodal<?=$id?>">
        <input type="hidden" name="urun_aciklamamodal[]" id="urun_aciklamamodal<?=$id?>">

    </div>

</div><!-- Col -->

 */