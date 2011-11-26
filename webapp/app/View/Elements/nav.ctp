<div class="topbar" data-dropdown="dropdown">
	<div class="fill">
		<div class="container">
			<a class="brand" href="/">CakePlate</a>
			<ul id="mainNav" class="nav">
				<li id="mainNavNews"><a href="/">News</a></li>		
				<li id="mainNavInfo" class="dropdown" data-dropdown="dropdown" >
					<a href="#" class="dropdown-toggle">Info</a>
					<ul class="dropdown-menu">
						<li><a href="/info/">About us</a></li>
						<li><a href="/info/contact/">Contact</a></li>
					</ul>
				</li>				
				<li id="mainNavFaq"><a href="/faqs/">FAQs</a></li>											
			</ul>
			<?php
			echo $this->Form->create('User', array('action' => 'login', 'class'=>'pull-right'));
			echo $this->Form->input('email', array('label'=>false,'div'=>false,'class'=>'span2 placeholder','title'=>'Email','placeholder'=>'Email', 'error'=>false));
			echo $this->Form->input('password', array('type'=>'password','label'=>false,'div'=>false,'class'=>'span2 placeholder','title'=>'Password','placeholder'=>'Password', 'error'=>false));
			?>			
			<button class="btn primary" type="submit">Sign in</button>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>