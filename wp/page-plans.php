<?php 
get_header();
/** Template Name: Planos */
?>
<div class="page">
	<?php if(have_posts()): while(have_posts()): the_post(); ?> 

		<section class="banners min clouds">

		</section>
		<section class="container">
			<h1 class="post-title" ><?php the_title(); ?></h1>
			<article class="text">                           
				<?php the_content(); ?>
			</article>
		</section>
		<section class="container">
			<nav class="tw-block md:tw-flex text-center plans-nav">
				<a class="tw-w-full md:tw-w-auto tw-mb-3 md:tw-mb-0" href="javascript://" data-tab-toggle="monthly">Plano Mensal</a>
				<a class="tw-w-full md:tw-w-auto tw-mb-3 md:tw-mb-0" href="javascript://"  data-tab-toggle="monthly_fidelity">Plano Mensal Fidadelidade</a>
			</nav>
			
			<div class="scroll-on-mobile">
			<?php foreach (['monthly', 'monthly_fidelity', 'yearly'] as $index => $type): ?>
				<div style="<?php echo $index === 0 ? '' : 'display:none' ?>" data-tab-item="<?php echo $type ?>">
					<table class="plans tw-mb-12">
					<thead>
						<tr>
							<td class="plan-header is-empty"></td>
							<?php foreach (get_custom_post_type('plan', '-1') as $plan): $terms = wp_get_post_terms( $plan->ID, 'pacotes' ); ?>
								<?php $group = get_field($type, $plan->ID) ?>
								<td class="plan-header <?php echo get_field('is_featured', $plan->ID) ? 'is-featured' : '' ?>">
									<div class="plan-header-holder">
										<?php if ($group['plan_badge']): ?>
											<span class="plan-badge"><?php echo $group['plan_badge'] ?></span>
										<?php endif ?>
										<img src="<?php echo get_the_post_thumbnail_url($plan->ID, 'full') ?>">
										<h4 class="plan-title"><?php echo $plan->post_title ?></h4>
										<span class="plan-price">
											<span>R$ </span>
											<strong><?php echo $group['plan_price'] ?></strong>
										</span>
										<?php if ($group['plan_first_line']): ?>
											
										<?php endif ?>
										<small class="plan-period">
											<?php echo $group['plan_first_line'] ?>
										</small>
										<span class="plan-economy">
											<?php if ($group['plan_second_line']): ?>
											<span><?php echo $group['plan_second_line'] ?></span>
											<?php endif ?>
											<?php if ($group['plan_third_line']): ?>
											<strong><?php echo  $group['plan_third_line'] ?></strong>
											<?php endif ?>
										</span>
										<form id="ctc" action="<?php echo home_url('/carrinho/add');?>" method="post">
											<input type="hidden" name="produto" value="<?php echo $terms[0]->term_id;?>">
											<input name="t" value="1" type="hidden">
											<input name="radio-stacked" value="1" type="hidden">
											<button class="plan-contract">Contratar</button>
											<a href="<?php echo home_url('/pacotes/' . $plan->post_name . '/');?>" class="plan-more">Saiba mais</a>
										</form>
									</div>
								</td>
							<?php endforeach ?>

						</tr>
					</thead>
					<tbody>
						<?php foreach (get_taxonomy_post_type('plan_features') as $feature): ?>
							<tr class="plan-row">
								<td>
									<span class="plan-row-label" data-dropdown-toggle="<?php echo $feature->term_id ?>">
										<?php if (! empty(get_taxonomy_post_type('plan_features', $feature->term_id))): ?>
											<a href="javascript://"><?php echo $feature->name ?>
												<i class="fa fa-chevron-down"></i>
											</a>
										<?php else: ?>
											<strong><?php echo $feature->name ?></strong>
										<?php endif; ?>
										<?php if ($feature->category_description): ?>
										<i  data-toggle="popover" data-html="true" data-trigger="hover" data-content="<?php echo $feature->category_description ?>" class="fa tw-cursor-pointer fa-question-circle"></i>
										<?php endif ?>
									</span>
								</td>
								<?php foreach ( get_custom_post_type('plan', '-1') as $plan): ?>
									<?php foreach (get_field('plans', $feature) as $item): ?>
										<?php if ($item['plan']->ID == $plan->ID): ?>
											<td class="<?php echo get_field('is_featured', $item['plan']->ID) ? 'is-featured' : '' ?>">
												<?php if ($item['value'] == 'NAO'): ?>
													<i class="fa fa-times"></i>
												<?php elseif($item['value'] == 'SIM'): ?>
													<i class="fa fa-check"></i>
												<?php else: ?>
													<?php echo $item['value'] ?>
												<?php endif ?>
											</td>
										<?php endif ?>

									<?php endforeach ?>
								<?php endforeach ?>
							</tr>

							<?php if (! empty(get_taxonomy_post_type('plan_features', $feature->term_id))): ?>
								<?php foreach (get_taxonomy_post_type('plan_features', $feature->term_id) as $subitem): ?>
									<tr class="plan-row" data-dropdown-item="<?php echo $feature->term_id ?>">
										<td>
											<span class="plan-row-label">
												<small><?php echo $subitem->name ?></small>
											</span>
										</td>


										<?php foreach ( get_custom_post_type('plan', '-1') as $plan): ?>
											<?php $items = get_field('plans', $subitem);
											if($items): 
												foreach ($items as $item): ?>
													<?php if ($item['plan']->ID == $plan->ID): ?>
														<td class="<?php echo get_field('is_featured', $item['plan']->ID) ? 'is-featured' : '' ?>">
															<?php if ($item['value'] == 'NAO'): ?>
																<i class="fa fa-times"></i>
															<?php elseif($item['value'] == 'SIM'): ?>
																<i class="fa fa-check"></i>
															<?php else: ?>
																<?php echo $item['value'] ?>
															<?php endif ?>
														</td>
													<?php endif ?>
												<?php endforeach ?>
											<?php endif ?>
										<?php endforeach ?>


									</tr>
								<?php endforeach ?>

							<?php endif ?>

						<?php endforeach ?>

					</tbody>
					<tfoot>
						<tr class="plan-row">
							<td class="plan-footer is-empty"></td>
							<?php foreach (get_custom_post_type('plan', '-1') as $plan): ?>
								<td class="plan-footer <?php echo get_field('is_featured', $plan->ID) ? 'is-featured' : '' ?>">
									<form id="ctc" action="<?php echo home_url('/carrinho/add');?>" method="post">
										<input type="hidden" name="produto" value="<?php echo $terms[0]->term_id;?>">
										<input name="t" value="1" type="hidden">
										<input name="radio-stacked" value="1" type="hidden">
										<button class="plan-contract">Contratar</button>
										<a href="<?php echo home_url('/pacotes/' . $plan->post_name . '/');?>" class="plan-more">Saiba mais</a>
									</form>
								</td>
							<?php endforeach ?>
						</tr>
					</tfoot>
				</table>
				</div>

			<?php endforeach ?>
			</div>
		</section>
	<?php endwhile; endif; ?>
	<?php 
	get_template_part('section', 'experimente');

	get_template_part('section', 'depoimentos');
	?>
</div>
<div class="overlay"></div>

<?php get_footer() ?>

