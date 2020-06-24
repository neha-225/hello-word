<?php foreach($product1 as $product1 ){?>                      <a href="<?php echo base_url(). 'home/get_product?id='.$product1->id?>">

      <div class="product-meta-data">
         <div class="line"></div>
                   
         <img  style="margin-left: 34%; margin-right: 34%;margin-top: 5%;margin-bottom: 2%; width: 30%;height: 50%"src="<?php echo base_url().CATEGORY_PATH.$product1->image;?>" alt="">
      
         <div>
            <h4 style="text-align: center;"><?php echo '₹ '; ?><?php echo "$product1->price/-";?></h4>
            <h4 style="text-align: center;"><?php echo "$product1->description";?></h4>
         </div>
         
      </div></a>
      <?php } ?>
<div class="button">
  
<a href="<?php echo base_url(). 'home/add_to_card?id='.$product1->id?>">
<button style="width: 15%;margin-left: 42%;text-align: center;"  type="submit" class="">Add to card</button></a><br>
</div>
<div class="button">
<br>
<button style="width: 15%;margin-left: 42%;text-align: center;"  type="submit" class="">By now  </button>

</div>

