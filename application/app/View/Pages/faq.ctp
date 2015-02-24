<div role="main" class="content default clearfix">
    <h2>Frequently (and not so frequently) Asked Questions</h2>

    <h3>How does this thing work?</h3>

    <p>It’s quite simple actually. When you input the band name the system pings last.fm database to check their basic info, picture and top ten tracks scrobbled in the
        last 6 months. That data is then parsed and sent to Grooveshark, where they respond with each SongID (an unique identifier for each song in their database), which I use to build the music player in
        the result page.<br/>
        The music player is hosted and maintained by Grooveshark, and that’s why I have no control over it (like changing its design or functionality).</p>

    <h3>The player doesn't load. All I see is that stupid Grooveshark logo spinning.</h3>

    <p>Try deleting your cookies and cache. If you don't want to delete all cookies try deleting the ones related to Grooveshark. Cleaning the
        <a href="http://www.macromedia.com/support/documentation/en/flashplayer/help/settings_manager07.html">flash cache</a> would help too.</p>

    <h3>How did you get access to the Last.fm and Grooveshark API?</h3>

    <p>The <a href="http://www.last.fm/api/intro">Last.fm API</a> access is free as long as you don’t make a fortune with it. It’s quite fast and as far as I know the API requests limit is quite
        high.

        The <a href="http://developers.grooveshark.com/">Grooveshark API</a> is also free, but you have to apply first and justify with what and why you are going to use it which usually takes time.<br/>
        When I started this website I used the <a href="http://www.tinysong.com/api">Tinysong API</a>, free and doesn’t require registration, but their limits are quite low. I asked like
        three times for a raise and they did it
        without any objections, but due to the popularity of TTBO I had to migrate to the Grooveshark API.</p>

    <h3>Which technologies did you use to make TTBO?</h3>

    <p>PHP and MySQL. First I started with an ugly, big and unmaintainable .php file that did EVERYTHING. <br/><br/>
        Homepage? <br/>
        index.php <br/>
        Band request? <br/>
        index.php <br/>
        Info and FAQ? <br/>
        index.php <br/><br/>

        It did <em>everything</em>.<br/>
        I was starting, there was no cache and the massive flow I received when I posted in r/music the first time broke everything. Even my webhost fired me as a
        client, saying my code was breaking everything and affecting other users of the shared host.</p>

    <p>After the first crash many users suggested to try caching everything I could. So I had to learn MySQL. Most web developers would cringe
        after a quick look at my old database and old code. </p>

    <p>Fortunately I started working as a PHP developer in an advertising agency, learned to code properly, and redid the whole thing in CakePHP. <br/></p>

    <p>We are currently running on CakePHP 2. <em>I wuv u CakePHP.</em></p>

    <h3>Why your English sucks soooo much?</h3>

    <p>I’m from Argentina and Spanish is my main language. I recognize my English sucks. Past tense is a bitch. I also have a lot of trouble using "on", "in" and "at" correctly.</p>


</div>