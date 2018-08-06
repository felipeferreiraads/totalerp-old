<table class="plans tw-mb-12">
	<thead>
		<tr>
			<td class="plan-header is-empty"></td>
			<?php foreach (get_posts_of_taxonomy('plan', 'plan_type', $type->term_id) as $plan): ?>
				<td class="plan-header <?php echo get_field('is_featured', $plan->ID) ? 'is-featured' : '' ?>">
					<div class="plan-header-holder">
						<?php if ($badge = get_field('plan_badge', $plan->ID)): ?>
							<span class="plan-badge"><?php echo $badge ?></span>
						<?php endif ?>
						<img src="<?php echo get_the_post_thumbnail_url($plan->ID, 'full') ?>">
						<h4 class="plan-title"><?php echo $plan->post_title ?></h4>
						<span class="plan-price">
							<span>R$ </span>
							<strong><?php echo get_field('plan_price', $plan->ID) ?></strong>
						</span>
						<small class="plan-period"><?php echo get_field('plan_first_line', $plan->ID) ?></small>
						<span class="plan-economy">
							<span><?php echo get_field('plan_second_line', $plan->ID) ?></span>
							<strong><?php echo get_field('plan_third_line', $plan->ID) ?></strong>
						</span>
						<a class="plan-contract" href="#">Contratar</a>
						<a href="#" class="plan-more">Saiba mais</a>

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
						<i class="fa fa-question-circle"></i>
					</span>
				</td>
				<?php foreach ( get_posts_of_taxonomy('plan', 'plan_type', $type->term_id) as $plan): ?>
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


						<?php foreach ( get_posts_of_taxonomy('plan', 'plan_type', $type->term_id) as $plan): ?>
							<?php foreach (get_field('plans', $subitem) as $item): ?>
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
				<?php endforeach ?>

			<?php endif ?>

		<?php endforeach ?>

	</tbody>
	<tfoot>
		<tr class="plan-row">
			<td class="plan-footer is-empty"></td>
			<?php foreach (get_posts_of_taxonomy('plan', 'plan_type', $type->term_id) as $plan): ?>
				<td class="plan-footer <?php echo get_field('is_featured', $plan->ID) ? 'is-featured' : '' ?>">
					<a href="" class="plan-contract">Contratar</a>
					<a href="" class="plan-more">Saiba Mais</a>
				</td>
			<?php endforeach ?>
		</tr>
	</tfoot>
</table>
