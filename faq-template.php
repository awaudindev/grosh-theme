<?php
/**
 * Template Name: faq template
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package grosh
 */
get_header(); 
?>
<div class="page-header">
	<div class="container">
		<div class="row">
			<div class="col-md-12"><?php the_breadcrumb(); ?></div>
		</div>
	</div>
</div>
	<div id="primary" class="content-area content-apps">
		<main id="main" class="site-main" role="main">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<?php
						while ( have_posts() ) : the_post();

							get_template_part( 'components/page/content', 'page' );

						endwhile; // End of the loop.
						?>
						<?php 
							$terms = $grosh_meta['faq'];
						?>
						<div class="clearfix">
							<ul class="nav nav-tabs" role="tablist">
								<?php $i = 0; foreach ( $terms as $id ) { $term = get_term( $id ); $class = $i == 0 ? "active":""?>
						            <li role="presentation" class="<?php echo $class; ?>"><a href="#<?php echo $term->slug; ?>faq" aria-controls="home" role="tab" data-toggle="tab"><?php echo $term->name; ?></a></li>
						        <?php $i++;} ?>
							 </ul>
							 <div class="tab-content marTop30">
							 	<!--[start:lightingFaq]-->
							 	<?php $i = 0; foreach ( $terms as $id ) { $term = get_term( $id ); $class = $i == 0 ? "active":""?>
								    <div role="tabpanel" class="tab-pane <?php echo $class; ?>" id="<?php echo $term->slug; ?>faq">
								    	<div class="clearfix">
											<div class="panel-group" id="accordion<?php echo $id; ?>" role="tablist" aria-multiselectable="true">
												<?php
													$args = array(
													'post_type' => 'faq_item',
													'tax_query' => array(
													    array(
													    'taxonomy' => 'faq_category',
													    'field' => 'id',
													    'terms' => $id
													     )
													  )
													);
													$query = new WP_Query( $args );
													while ($query->have_posts()) : $query->the_post();
													$idchild = get_the_ID();
													?>
													<div class="panel panel-default">
												    	<div class="panel-heading" role="tab" id="heading-2">
													      	<h4 class="panel-title">
													        	<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion<?php echo $id; ?>" href="#collapse-<?php echo $idchild; ?>" aria-expanded="false" aria-controls="collapseTwo"><span class="glyphicon glyphicon-plus"></span>
													          	<?php the_title(); ?>
													        	</a>
													      	</h4>
												    	</div>
												    	<div id="collapse-<?php echo $idchild; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-2">
												      		<div class="panel-body">
												        		<?php the_content(); ?>
												      		</div>
												    	</div>
												  	</div>
													<?php
													endwhile;
													wp_reset_postdata(); 
												?>
											</div>
										</div>	
								    </div>
								<?php $i++;} ?>

							    <div role="tabpanel" class="tab-pane" id="lightingFaq">
							    	<div class="clearfix">
										<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
										  	<div class="panel panel-default">
											    <div class="panel-heading" role="tab" id="heading-1">
											     	<h4 class="panel-title">
												        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-1" aria-expanded="true" aria-controls="collapseOne"><span class="glyphicon glyphicon-plus"></span>
												          Collapsible Group Item #1
												        </a>
											      	</h4>
											    </div>
										    	<div id="collapse-1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-1">
											      	<div class="panel-body">
											        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
											      	</div>
										    	</div>
										  	</div>
										  	<div class="panel panel-default">
										    	<div class="panel-heading" role="tab" id="heading-2">
											      	<h4 class="panel-title">
											        	<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-2" aria-expanded="false" aria-controls="collapseTwo"><span class="glyphicon glyphicon-plus"></span>
											          Collapsible Group Item #2
											        	</a>
											      	</h4>
										    	</div>
										    	<div id="collapse-2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-2">
										      		<div class="panel-body">
										        		Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
										      		</div>
										    	</div>
										  	</div>
										  	<div class="panel panel-default">
										    	<div class="panel-heading" role="tab" id="heading-3">
										      		<h4 class="panel-title">
											        	<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-3" aria-expanded="false" aria-controls="collapseThree"><span class="glyphicon glyphicon-plus"></span>
											         	 Collapsible Group Item #3
											        	</a>
										      		</h4>
										    	</div>
										    	<div id="collapse-3" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-3">
												    <div class="panel-body">
												        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
												    </div>
										    	</div>
											</div>
										</div>
									</div>	
							    </div>
							    <!--[end:lightingFaq]-->

							  </div>
						</div>
						
					</div>
				</div>
			</div>	
		</main>
	</div>
<?php
get_footer();