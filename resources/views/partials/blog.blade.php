@if($page === 'home')
<section class="home-blog">
    <div class="wrap">
        <div class="home-blog-head">
            <span class="home-blog-label">BLOG</span>
            <h2>Insights &amp; Guides</h2>
            <p>Practical advice on video quality, formats, compatibility, and safer downloads.</p>
        </div>
        @if(count($posts))
            @php $featured = $posts[0]; $sidePosts = array_slice($posts, 1, 3); @endphp
            <div class="editorial-blog-grid">
                <article class="featured-post">
                    <a class="featured-post-image" href="{{ route('blog.show', $featured['slug']) }}">
                        <img src="{{ asset($featured['image']) }}" alt="{{ $featured['image_alt'] ?? $featured['title'] }}" loading="lazy">
                    </a>
                    <div class="editorial-meta"><span>HDVideo Team</span><time>{{ $featured['published'] }}</time></div>
                    <h3><a href="{{ route('blog.show', $featured['slug']) }}">{{ $featured['title'] }}</a></h3>
                    <p>{{ $featured['excerpt'] }}</p>
                </article>
                <div class="side-posts">
                    @foreach($sidePosts as $post)
                        <article class="side-post">
                            <a class="side-post-image" href="{{ route('blog.show', $post['slug']) }}"><img src="{{ asset($post['image']) }}" alt="{{ $post['image_alt'] ?? $post['title'] }}" loading="lazy"></a>
                            <div class="side-post-copy">
                                <div class="editorial-meta"><span>HDVideo Team</span><time>{{ $post['published'] }}</time></div>
                                <h3><a href="{{ route('blog.show', $post['slug']) }}">{{ $post['title'] }}</a></h3>
                                <p>{{ $post['excerpt'] }}</p>
                            </div>
                        </article>
                    @endforeach
                    <a class="view-all-posts" href="{{ route('blog') }}">View all guides <span>→</span></a>
                </div>
            </div>
        @endif
    </div>
</section>
@else
<section class="dark-blog-section">
    <div class="wrap">
        <div class="dark-blog-header">
            <h2 class="dark-blog-title">Our Blogs</h2>
            <div class="dark-search">
                <input type="text" placeholder="Search blogs" aria-label="Search blogs">
            </div>
        </div>
        
        @if(count($posts) > 0)
            @php 
                $featured = $posts[0]; 
                $topSidePosts = array_slice($posts, 1, 2); 
                $remainingPosts = array_slice($posts, 3);
            @endphp
            
            <div class="dark-top-grid">
                <!-- Large Featured Post -->
                <article class="dark-post-card">
                    <a class="dark-post-cover" href="{{ route('blog.show', $featured['slug']) }}">
                        <img src="{{ asset($featured['image']) }}" alt="{{ $featured['title'] }}" loading="lazy">
                    </a>
                    <div class="dark-post-meta">
                        <span>{{ $featured['author'] ?? 'HDVideo Team' }}</span>
                        <time>{{ $featured['published'] }}</time>
                    </div>
                    <h3 class="dark-post-title"><a href="{{ route('blog.show', $featured['slug']) }}">{{ $featured['title'] }}</a></h3>
                    <p class="dark-post-excerpt">{{ $featured['excerpt'] }}</p>
                </article>
                
                <!-- Side Posts -->
                <div class="dark-side-stack">
                    @foreach($topSidePosts as $post)
                        <article class="dark-post-card">
                            <a class="dark-post-cover" href="{{ route('blog.show', $post['slug']) }}">
                                <img src="{{ asset($post['image']) }}" alt="{{ $post['title'] }}" loading="lazy">
                            </a>
                            <div class="dark-post-meta">
                                <span>{{ $post['author'] ?? 'HDVideo Team' }}</span>
                                <time>{{ $post['published'] }}</time>
                            </div>
                            <h3 class="dark-post-title"><a href="{{ route('blog.show', $post['slug']) }}">{{ $post['title'] }}</a></h3>
                            <p class="dark-post-excerpt">{{ $post['excerpt'] }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
            
            <div class="dark-bottom-grid">
                <!-- Remaining Posts Grid -->
                <div class="dark-main-posts">
                    @foreach($remainingPosts as $post)
                        <article class="dark-post-card">
                            <a class="dark-post-cover" href="{{ route('blog.show', $post['slug']) }}">
                                <img src="{{ asset($post['image']) }}" alt="{{ $post['title'] }}" loading="lazy">
                            </a>
                            <div class="dark-post-meta">
                                <span>{{ $post['author'] ?? 'HDVideo Team' }}</span>
                                <time>{{ $post['published'] }}</time>
                            </div>
                            <h3 class="dark-post-title"><a href="{{ route('blog.show', $post['slug']) }}">{{ $post['title'] }}</a></h3>
                            <p class="dark-post-excerpt">{{ $post['excerpt'] }}</p>
                        </article>
                    @endforeach
                </div>
                
                <!-- Sidebar -->
                <aside class="dark-sidebar">
                    <div class="dark-widget">
                        <h3 class="dark-widget-title"><span>❖</span> Popular Categories</h3>
                        <ul class="dark-category-list">
                            <li><div class="dark-category-icon">🎥</div> Video Formats</li>
                            <li><div class="dark-category-icon">📺</div> HD Quality</li>
                            <li><div class="dark-category-icon">📱</div> Supported Platforms</li>
                            <li><div class="dark-category-icon">🔒</div> Safe Downloading</li>
                        </ul>
                    </div>
                    
                    <div class="dark-widget">
                        <h3 class="dark-widget-title"><span>📄</span> Quick Support</h3>
                        <form action="#" method="POST" onsubmit="event.preventDefault();">
                            <div class="dark-form-group">
                                <input type="email" placeholder="Email" required>
                            </div>
                            <div class="dark-form-group">
                                <input type="text" placeholder="Subject" required>
                            </div>
                            <div class="dark-form-group">
                                <textarea placeholder="Message" required></textarea>
                            </div>
                            <div class="dark-form-row">
                                <button type="submit" class="dark-btn-primary">Send</button>
                                <button type="button" class="dark-btn-secondary">FAQ</button>
                            </div>
                        </form>
                    </div>
                </aside>
            </div>
        @endif
    </div>
</section>
@endif
