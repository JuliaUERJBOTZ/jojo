<style>
.view {
   width: 100%;
   height: auto;
   margin-bottom:10px ;
   padding:0px !important;
   float: left;
   <?php if($PVGM_Image_Border=="yes"){?>
   border:5px solid #000000 ;
   <?php } ?>
   overflow: hidden;
   position: relative;
   text-align: center;
   -webkit-box-shadow: 1px 1px 2px #e6e6e6;
   -moz-box-shadow: 1px 1px 2px #e6e6e6;
   box-shadow: 1px 1px 2px #e6e6e6;
   cursor: default;
   background: #fff url(../images/bgimg.jpg) no-repeat center center;
}
.view .mask,.view .content {
   width: 100%;
   height: 100%;
   position: absolute;
   overflow: hidden;
   top: 0;
   left: 0;
}
.view img {
   display: block;
   position: relative;
   width:100%;
   height:auto;
   
}
.view h2 {
   color: #fff;
   text-align: center;
   position: relative;
   font-size: <?php echo $PVGM_Lable_Font_Size; ?>px;
   padding: 10px;
   background:  rgba(<?php echo $Label_Bg_Color; ?>,0.8 ) !important;
   color:  rgba(<?php echo $Label_Text_Color; ?>,1 ) !important;
   margin: 20px 0 0 0 !important;
   font-family: <?php echo $PVGM_Font_Style; ?>;
   font-weight: 500;
}
.view p {
   font-family: <?php echo $PVGM_Font_Style; ?>;
   font-style: italic;
   font-size: <?php echo $PVGM_Desc_Font_Size; ?>px;
   position: relative;
   color: <?php echo $PVGM_Descp_Text_Color; ?>;
   padding: 10px 20px 20px;
   text-align: center;
}
.view a.info {
   display: inline-block;
   text-decoration: none;
   padding: 7px 14px;
   background:  rgba(<?php echo $Label_Bg_Color; ?>,0.8 ) !important;
   color: <?php echo $PVGM_Label_Text_Color; ?>;
   text-transform: uppercase;
   -webkit-box-shadow: 0 0 1px #000;
   -moz-box-shadow: 0 0 1px #000;
   box-shadow: 0 0 1px #000;
}
.view a.info: hover {
   -webkit-box-shadow: 0 0 5px #000;
   -moz-box-shadow: 0 0 5px #000;
   box-shadow: 0 0 5px #000;
}
</style>