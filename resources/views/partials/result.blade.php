<div class="result">
    @php
        $qualityNumber = function ($format) {
            $quality = (string) ($format['quality'] ?? '');
            preg_match('/\d+(?:\.\d+)?/', $quality, $matches);
            return isset($matches[0]) ? (float) $matches[0] : 0;
        };
        $videoFormats = collect($result['resources'] ?? [])
            ->where('category', 'video')
            ->sortByDesc($qualityNumber)
            ->values();
        $audioFormats = collect($result['resources'] ?? [])
            ->where('category', 'audio')
            ->sortByDesc($qualityNumber)
            ->values();
        $duration = (int) ($result['duration'] ?? 0);
    @endphp
    <div class="result-layout">
        <aside class="media-summary">
            @if(!empty($result['thumbnail']))
                <div class="media-thumb-wrap"><img class="media-thumb" src="{{ $result['thumbnail'] }}" alt="{{ $result['title'] ?? 'Video thumbnail' }}"><span class="media-play">▶</span></div>
            @else
                <div class="media-thumb-wrap"><div class="media-thumb"></div><span class="media-play">▶</span></div>
            @endif
            <div class="media-platform">{{ $result['platform'] }} · {{ $result['host'] }}</div>
            <h2 class="media-title">{{ $result['title'] ?? 'Media Found' }}</h2>
            @if($duration > 0)
                <span class="media-duration">Time {{ gmdate($duration >= 3600 ? 'H:i:s' : 'i:s', $duration) }}</span>
            @endif
        </aside>
        <div class="format-list">
            @if($videoFormats->isNotEmpty())
                <section class="format-section">
                    <h3 class="format-heading"><span class="format-heading-mark">▶</span>Video</h3>
                    @foreach($videoFormats as $format)
                        @php
                            $extension = strtolower($format['format'] ?: 'mp4');
                            $filename = \Illuminate\Support\Str::slug($result['title'] ?? 'media') . '-' . strtolower($format['quality']) . '.' . $extension;
                            $source = !empty($format['download_url']) ? rtrim(strtr(base64_encode($format['download_url']), '+/', '-_'), '=') : null;
                            $downloadUrl = $source ? \Illuminate\Support\Facades\URL::temporarySignedRoute('media.download', now()->addMinutes(20), ['source' => $source, 'name' => $filename]) : null;
                            $prepareUrl = !empty($format['prepare_token']) ? \Illuminate\Support\Facades\URL::temporarySignedRoute('plugin.prepare', now()->addMinutes(25), ['token' => $format['prepare_token'], 'name' => $filename]) : null;
                        @endphp
                        <div class="format-row">
                            <span class="format-badge">{{ $format['format'] }}</span>
                            <span class="format-quality">{{ strtoupper($format['quality']) }}</span>
                            <span class="format-size">{{ $format['size'] }}</span>
                            @if($downloadUrl)
                                <a class="download-link direct-download" href="{{ $downloadUrl }}"><svg class="download-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v12"/><path d="M7 10l5 5 5-5"/><path d="M5 21h14"/></svg><span class="download-label">Download</span></a>
                            @elseif($prepareUrl)
                                <button class="download-link prepare-download" type="button" data-prepare-url="{{ $prepareUrl }}"><svg class="download-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v12"/><path d="M7 10l5 5 5-5"/><path d="M5 21h14"/></svg><span class="download-label">Download</span></button>
                            @endif
                        </div>
                    @endforeach
                </section>
            @endif
            @if($audioFormats->isNotEmpty())
                <section class="format-section">
                    <h3 class="format-heading"><span class="format-heading-mark">♫</span>Audio</h3>
                    @foreach($audioFormats as $format)
                        @php
                            $extension = strtolower($format['format'] ?: 'mp3');
                            $filename = \Illuminate\Support\Str::slug($result['title'] ?? 'media-audio') . '-' . strtolower($format['quality']) . '.' . $extension;
                            $source = !empty($format['download_url']) ? rtrim(strtr(base64_encode($format['download_url']), '+/', '-_'), '=') : null;
                            $downloadUrl = $source ? \Illuminate\Support\Facades\URL::temporarySignedRoute('media.download', now()->addMinutes(20), ['source' => $source, 'name' => $filename]) : null;
                            $prepareUrl = !empty($format['prepare_token']) ? \Illuminate\Support\Facades\URL::temporarySignedRoute('plugin.prepare', now()->addMinutes(25), ['token' => $format['prepare_token'], 'name' => $filename]) : null;
                        @endphp
                        <div class="format-row">
                            <span class="format-badge">{{ $format['format'] }}</span>
                            <span class="format-quality">{{ strtoupper($format['quality']) }}</span>
                            <span class="format-size">{{ $format['size'] }}</span>
                            @if($downloadUrl)
                                <a class="download-link direct-download" href="{{ $downloadUrl }}"><svg class="download-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v12"/><path d="M7 10l5 5 5-5"/><path d="M5 21h14"/></svg><span class="download-label">Download</span></a>
                            @elseif($prepareUrl)
                                <button class="download-link prepare-download" type="button" data-prepare-url="{{ $prepareUrl }}"><svg class="download-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v12"/><path d="M7 10l5 5 5-5"/><path d="M5 21h14"/></svg><span class="download-label">Download</span></button>
                            @endif
                        </div>
                    @endforeach
                </section>
            @endif
            @if($videoFormats->isEmpty() && $audioFormats->isEmpty())
                <p class="empty-formats">No usable public formats were found for this link.</p>
            @endif
        </div>
    </div>
    <p class="result-note">Use only content you own or have permission to save.</p>
</div>
