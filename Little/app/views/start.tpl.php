@defineSection('title')
Titre trop bien
@endDefineSection
@defineSection('content')

	@defineSection('content')
	
		<pre>Ceci est un header</pre>
	
	@endDefineSection
	
	@include('header')
	
	@defineSection('content')
	
		<pre>Ceci est un footer</pre>
	
	@endDefineSection
	
	@include('footer')
	
@endDefineSection

@include('main')