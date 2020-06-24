  <?php foreach($product as $product ){?>                      <a href="<?php echo base_url(). 'home/get_product?id='.$product->id?>">
                       
                     <img style="height:25%; width: 15%; margin-left: 3%;margin-right: 5%; "src="<?php echo base_url().CATEGORY_PATH.$product->image;?>" alt=""></a>
                  
            
                   
 <?php } ?>
