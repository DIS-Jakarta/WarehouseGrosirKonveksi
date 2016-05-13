<aside id="sidebar">
		<!-- <form class="quick_search">
			<input type="text" value="Quick Search" onfocus="if(!this._haschanged){this.value=''};this._haschanged=true;">
		</form> -->
		<h3>Menu Utama</h3>
		<ul>
			<?php
			if(isset($menuStok)){
			foreach($menuStok as $item){
			echo '<li class="icn_new_article"><a href="' . site_url($item['menu_url']) . '">' . $item['menu_desc'] . '</a></li>';
			}
			}
			?>
			<?php
			if(isset($menuCekStok)){
			foreach($menuCekStok as $item){
			echo '<li class="icn_search"><a href="' . site_url($item['menu_url']) . '">' . $item['menu_desc'] . '</a></li>';
			}
			}
			?>
		</ul>
		<h3>Pengaturan</h3>
		<ul>
			<?php
			if(isset($menuItem)){
			foreach ($menuItem as $item){
			echo '<li class="icn_edit_article"><a href="' . site_url($item['menu_url']) . '">' . $item['menu_desc'] . '</a></li>';
			}
			}
			if(isset($menuUser)){
			foreach ($menuUser as $item){
			echo '<li class="icn_add_user"><a href="' . site_url($item['menu_url']) . '">' . $item['menu_desc'] . '</a></li>';
			}
			}
			if(isset($menuGrup)){
			foreach ($menuGrup as $item){
			echo '<li class="icn_add_user"><a href="' . site_url($item['menu_url']) . '">' . $item['menu_desc'] . '</a></li>';
			}
			}
			if(isset($menuGrupmenu)){
			foreach ($menuGrupmenu as $item){
			echo '<li class="icn_view_users"><a href="' . site_url($item['menu_url']) . '">' . $item['menu_desc'] . '</a></li>';
			}
			}
			?>
			<!--<li class="icn_add_user"><a href="#">User</a></li>
			<li class="icn_view_users"><a href="#">Group menu</a></li>-->
		</ul>
		<footer>
			<hr />
			<p><strong>Copyright &copy; 2016 Digital Information Solution Jakarta ( DIS-J )</strong></p>
			<!-- <p>Theme by <a href="http://www.medialoot.com">Digital Information Solution</a></p> -->
		</footer>
	</aside><!-- end of sidebar -->