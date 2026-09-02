@extends('layouts.admin_master')

@section('title', 'Analytics Dashboard')
@section('header_icon')<i class="fas fa-chart-line" style="color:#3668de"></i>@endsection
@section('header_title', 'Analytics Dashboard')
@section('topbar_actions')
<form method="POST" action="{{ route('admin.publish_draft_blogs') }}" onsubmit="return confirm('Publish all draft blogs now?');" style="display:inline">
    @csrf
    <button type="submit" class="btn-preview" style="cursor:pointer"><i class="fas fa-upload"></i> Publish Draft Blogs</button>
</form>
<form method="POST" action="{{ route('admin.generate_trending_blog') }}" onsubmit="return confirm('Generate and publish a new Google Trends blog now?');" style="display:inline">
    @csrf
    <button type="submit" class="btn-preview" style="cursor:pointer"><i class="fas fa-wand-magic-sparkles"></i> Generate Trending Blog</button>
</form>
@endsection

@push('styles')
<style>
    .dash-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:1.4rem}.dash-head h2{font-size:1.15rem;color:#fff}.dash-head p{font-size:.78rem;color:#738093;margin-top:.25rem}.live{display:flex;align-items:center;gap:.5rem;color:#547ee1;font-size:.76rem}.live:before{content:"";width:8px;height:8px;background:#3668de;border-radius:50%;box-shadow:0 0 0 5px rgba(139, 92, 246,.1)}
    .stats{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1rem}.stat{background:#161b27;border:1px solid #252b39;border-radius:15px;padding:1.15rem;position:relative;overflow:hidden}.stat:after{content:"";position:absolute;right:-20px;top:-25px;width:85px;height:85px;border-radius:50%;background:var(--glow);opacity:.08}.stat-top{display:flex;justify-content:space-between;align-items:center}.stat-icon{width:39px;height:39px;border-radius:11px;display:grid;place-items:center;background:var(--glow);color:var(--color)}.stat small{font-size:.68rem;color:#647085}.stat strong{display:block;font-size:1.65rem;color:#fff;margin-top:.85rem}.stat label{display:block;font-size:.75rem;color:#8791a2;margin-top:.25rem}
    .grid-2{display:grid;grid-template-columns:1.65fr 1fr;gap:1rem;margin-bottom:1rem}.grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;margin-bottom:1rem}.panel{background:#161b27;border:1px solid #252b39;border-radius:15px;padding:1.2rem;min-width:0}.panel-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:1.1rem}.panel-head h3{font-size:.88rem;color:#f3f6fb}.panel-head span{font-size:.68rem;color:#667184}
    .bars{height:190px;display:flex;align-items:flex-end;gap:.65rem;border-bottom:1px solid #29303d;padding:10px 4px 0}.bar-col{height:100%;flex:1;display:flex;flex-direction:column;justify-content:flex-end;align-items:center;gap:.4rem}.bar-num{font-size:.66rem;color:#8c97a7}.bar{width:100%;max-width:46px;min-height:4px;background:linear-gradient(180deg,#4272e2,#163a8f);border-radius:6px 6px 2px 2px}.bar-day{font-size:.65rem;color:#697486;margin-bottom:-23px;transform:translateY(23px)}
    .rank{display:flex;flex-direction:column;gap:.85rem}.rank-row{display:grid;grid-template-columns:minmax(80px,1fr) 90px 38px;align-items:center;gap:.65rem}.rank-name{font-size:.75rem;color:#bdc5d0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.track{height:6px;background:#252c39;border-radius:5px;overflow:hidden}.fill{height:100%;background:#3668de;border-radius:5px}.rank-val{font-size:.7rem;color:#778294;text-align:right}
    .country-row,.source-row,.page-row{display:flex;align-items:center;gap:.75rem;padding:.65rem 0;border-bottom:1px solid #242a36}.country-row:last-child,.source-row:last-child,.page-row:last-child{border:0}.flag{width:32px;height:25px;border-radius:7px;background:#222b38;display:grid;place-items:center;font-size:.64rem;color:#537cdc;font-weight:700}.row-main{min-width:0;flex:1}.row-main b{display:block;color:#cdd4de;font-size:.75rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.row-main small{font-size:.65rem;color:#687386}.row-value{font-size:.75rem;color:#fff;font-weight:700}
    .table-panel{background:#161b27;border:1px solid #252b39;border-radius:15px;overflow:hidden}.table-title{padding:1rem 1.2rem;display:flex;justify-content:space-between;align-items:center}.table-title h3{font-size:.88rem;color:#fff}.refresh{border:1px solid #303846;background:#202633;color:#aeb8c7;border-radius:8px;padding:.5rem .75rem;cursor:pointer}.table-scroll{overflow:auto}table{width:100%;border-collapse:collapse;min-width:760px}th{font-size:.64rem;text-transform:uppercase;letter-spacing:.06em;color:#657084;background:#121722;text-align:left;padding:.7rem 1rem}td{font-size:.72rem;color:#aeb7c4;padding:.75rem 1rem;border-top:1px solid #222936}.pill{display:inline-flex;padding:.25rem .5rem;border-radius:6px;background:#222a36;color:#cbd3dd}.ok{color:#a78bfa}.fail{color:#fb7185}.ip{font-family:monospace;color:#8da0b7}
    @media(max-width:1100px){.stats{grid-template-columns:repeat(2,1fr)}.grid-3{grid-template-columns:1fr 1fr}.grid-3 .panel:last-child{grid-column:1/-1}}@media(max-width:760px){.content{padding:1rem!important}.stats,.grid-2,.grid-3{grid-template-columns:1fr}.grid-3 .panel:last-child{grid-column:auto}.dash-head{align-items:flex-start;gap:1rem}.stat strong{font-size:1.4rem}}
</style>
@endpush

@section('content')
<div class="dash-head"><div><h2>Website performance</h2><p>Downloads, visitors and traffic insights in one place.</p></div><div class="live"><span id="live-count">{{ number_format($stats['active_users']) }}</span> live now</div></div>

<div class="stats">
@php $cards = [
 ['total_downloads','Downloads','fa-download','#3668de','rgba(139, 92, 246,.18)','All-time successful'],
 ['failed_downloads','Failed','fa-circle-exclamation','#fb7185','rgba(251,113,133,.18)','All-time failed'],
 ['today_downloads','Today','fa-calendar-day','#60a5fa','rgba(96,165,250,.18)','Successful today'],
 ['week_downloads','This week','fa-calendar-week','#a78bfa','rgba(167,139,250,.18)','Successful this week'],
 ['active_users','Active users','fa-users','#fbbf24','rgba(251,191,36,.18)','Last 5 minutes'],
 ['today_visitors','Visitors today','fa-user-group','#225fee','rgba(139, 92, 246,.18)','Total visits today'],
 ['page_views','Page views','fa-eye','#f472b6','rgba(244,114,182,.18)','All tracked views'],
 ['success_rate','Success rate','fa-chart-pie','#a78bfa','rgba(139, 92, 246,.18)','Download completion'],
]; @endphp
@foreach($cards as $c)<div class="stat" style="--color:{{ $c[3] }};--glow:{{ $c[4] }}"><div class="stat-top"><div class="stat-icon"><i class="fas {{ $c[2] }}"></i></div><small>{{ $c[5] }}</small></div><strong>{{ number_format($stats[$c[0]], $c[0] === 'success_rate' ? 1 : 0) }}{{ $c[0] === 'success_rate' ? '%' : '' }}</strong><label>{{ $c[1] }}</label></div>@endforeach
</div>

<div class="grid-2">
 <div class="panel"><div class="panel-head"><h3>Downloads — last 7 days</h3><span>Successful downloads</span></div><div class="bars">@php $mx=max(array_column($weeklyData,'count') ?: [1]); @endphp @foreach($weeklyData as $d)<div class="bar-col"><span class="bar-num">{{ $d['count'] }}</span><div class="bar" style="height:{{ $mx ? max(4,round($d['count']/$mx*145)) : 4 }}px"></div><span class="bar-day">{{ $d['label'] }}</span></div>@endforeach</div></div>
 <div class="panel"><div class="panel-head"><h3>Top platforms</h3><span>All activity</span></div><div class="rank">@foreach($platformData as $p)<div class="rank-row"><span class="rank-name">{{ $p['name'] }}</span><div class="track"><div class="fill" style="width:{{ $p['pct'] }}%;background:{{ $p['color'] }}"></div></div><span class="rank-val">{{ $p['pct'] }}%</span></div>@endforeach</div></div>
</div>

<div class="grid-3">
 <div class="panel"><div class="panel-head"><h3>Top countries</h3><span>Visitors by location</span></div>@forelse($countryData as $c)<div class="country-row"><div class="flag">{{ $c->country_code ?: '—' }}</div><div class="row-main"><b>{{ $c->country ?: 'Unknown' }}</b><small>{{ number_format($c->users) }} users</small></div><span class="row-value">{{ number_format($c->views) }}</span></div>@empty<div class="row-main"><small>Country data appears when Cloudflare geo headers are enabled.</small></div>@endforelse</div>
 <div class="panel"><div class="panel-head"><h3>Traffic sources</h3><span>Where users come from</span></div>@forelse($sourceData as $s)<div class="source-row"><div class="flag"><i class="fas fa-arrow-trend-up"></i></div><div class="row-main"><b>{{ $s->source }}</b><small>Visits</small></div><span class="row-value">{{ number_format($s->views) }}</span></div>@empty<div class="row-main"><small>No traffic recorded yet.</small></div>@endforelse</div>
 <div class="panel"><div class="panel-head"><h3>Popular pages</h3><span>Most viewed URLs</span></div>@forelse($topPages as $p)<div class="page-row"><div class="flag"><i class="fas fa-file"></i></div><div class="row-main"><b>{{ $p->path }}</b><small>Page views</small></div><span class="row-value">{{ number_format($p->views) }}</span></div>@empty<div class="row-main"><small>No page views recorded yet.</small></div>@endforelse</div>
</div>

<div class="table-panel"><div class="table-title"><h3>Recent download activity & IPs</h3><button class="refresh" onclick="location.reload()"><i class="fas fa-rotate"></i> Refresh</button></div><div class="table-scroll"><table><thead><tr><th>Platform</th><th>Action</th><th>Format</th><th>IP address</th><th>Status</th><th>Title</th><th>Time</th></tr></thead><tbody>@forelse($recentLogs as $log)<tr><td><span class="pill">{{ $log->platform }}</span></td><td>{{ ucfirst($log->type) }}</td><td>{{ $log->format }} {{ $log->quality !== '—' ? '· '.$log->quality : '' }}</td><td class="ip">{{ $log->ip_address ?: '—' }}</td><td class="{{ $log->status ? 'ok' : 'fail' }}"><i class="fas {{ $log->status ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i> {{ $log->status ? 'Success' : 'Failed' }}</td><td>{{ \Illuminate\Support\Str::limit($log->title ?: '—', 34) }}</td><td>{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</td></tr>@empty<tr><td colspan="7" style="text-align:center;padding:2rem">No download activity yet.</td></tr>@endforelse</tbody></table></div></div>
@endsection

@push('scripts')<script>setTimeout(()=>location.reload(),60000);</script>@endpush
