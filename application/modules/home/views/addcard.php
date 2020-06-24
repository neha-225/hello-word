<table class="table table-responsive">
                                <thead>
                                    <tr>

                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Deccription</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                            <?php foreach($result as $result ){?>                     
                                        <td class="cart_product_img">
                                            <img style="height: 50px; width: " src="<?php echo base_url().CATEGORY_PATH.$result->image;?>" alt="">
                                        </td>
                                        <td class="cart_product_desc">
                                            <h5><?php echo $result->price?></h5>
                                        </td>
                                        <td class="price">
                                            <span><?php  echo $result->description;?></span>
                                        </td>
                                       
                                    </tr>
                                    <?php }?>
                                    
                                </tbody>
                            </table>
                          </div>
                        </div>
                            



                      <a href="<?php echo base_url();?>home/checkout">
<button style="width: 10%;margin-left: 2%"  type="submit" class="">checkout</button></a>