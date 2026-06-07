@extends('layouts.admin')
@section('title', 'CRM Reports')
@section('content')
<div class="crm2-page">
  <div class="crm2-header">
    <div>
      <h1 class="crm2-title"><i class="fas fa-file-chart-line"></i> CRM Reports</h1>
      <p class="crm2-subtitle">Generate detailed reports on sales, leads, activities and support.</p>
    </div>
  </div>

  {{-- Report Filter --}}
  <div class="crm2-card mb-4">
    <div class="crm2-card-body">
      <form method="GET" class="crm2-filter-form">
        <div class="filter-group">
          <label>Report Type</label>
          <select name="type" class="crm2-select" onchange="this.form.submit()">
            <option value="sales_summary" {{ $type==='sales_summary'?'selected':'' }}>Sales Summary</option>
            <option value="lead_report" {{ $type==='lead_report'?'selected':'' }}>Lead Report</option>
            <option value="activity_report" {{ $type==='activity_report'?'selected':'' }}>Activity Report</option>
            <option value="case_report" {{ $type==='case_report'?'selected':'' }}>Support Cases</option>
          </select>
        </div>
        <div class="filter-group">
          <label>From</label>
          <input type="date" name="from" value="{{ $from }}" class="crm2-input">
        </div>
        <div class="filter-group">
          <label>To</label>
          <input type="date" name="to" value="{{ $to }}" class="crm2-input">
        </div>
        <button type="submit" class="crm2-btn crm2-btn-primary"><i class="fas fa-search"></i> Generate</button>
      </form>
    </div>
  </div>

  {{-- Sales Summary --}}
  @if($type === 'sales_summary')
  <div class="crm2-charts-grid">
    <div class="crm2-card">
      <div class="crm2-card-header"><h3><i class="fas fa-funnel-dollar"></i> Deals by Stage</h3></div>
      <div class="crm2-card-body">
        @if($data['deals']->isEmpty())
          <div class="crm2-empty"><i class="fas fa-chart-bar"></i><p>No deals in this period.</p></div>
        @else
        <table class="crm2-table">
          <thead><tr><th>Stage</th><th>Count</th><th>Total Value</th></tr></thead>
          <tbody>
            @foreach($data['deals'] as $row)
            <tr>
              <td><span class="crm2-badge stage-{{ $row->stage }}">{{ ucwords(str_replace('_',' ',$row->stage)) }}</span></td>
              <td>{{ $row->count }}</td>
              <td>₹{{ number_format($row->total, 2) }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @endif
      </div>
    </div>
    <div class="crm2-card">
      <div class="crm2-card-header"><h3><i class="fas fa-file-invoice-dollar"></i> Invoices by Status</h3></div>
      <div class="crm2-card-body">
        @if($data['invoices']->isEmpty())
          <div class="crm2-empty"><i class="fas fa-file-invoice"></i><p>No invoices in this period.</p></div>
        @else
        <table class="crm2-table">
          <thead><tr><th>Status</th><th>Count</th><th>Total</th></tr></thead>
          <tbody>
            @foreach($data['invoices'] as $row)
            <tr>
              <td><span class="crm2-badge status-{{ $row->status }}">{{ ucwords(str_replace('_',' ',$row->status)) }}</span></td>
              <td>{{ $row->count }}</td>
              <td>₹{{ number_format($row->total, 2) }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @endif
      </div>
    </div>
  </div>

  {{-- Lead Report --}}
  @elseif($type === 'lead_report')
  <div class="crm2-charts-grid">
    <div class="crm2-card">
      <div class="crm2-card-header"><h3><i class="fas fa-user-tag"></i> Leads by Status</h3></div>
      <div class="crm2-card-body">
        @if($data['leads']->isEmpty())
          <div class="crm2-empty"><i class="fas fa-user-tag"></i><p>No leads in this period.</p></div>
        @else
        <table class="crm2-table">
          <thead><tr><th>Status</th><th>Count</th></tr></thead>
          <tbody>
            @foreach($data['leads'] as $row)
            <tr><td><span class="crm2-badge">{{ ucwords(str_replace('_',' ',$row->status ?? 'new')) }}</span></td><td>{{ $row->count }}</td></tr>
            @endforeach
          </tbody>
        </table>
        @endif
      </div>
    </div>
    <div class="crm2-card">
      <div class="crm2-card-header"><h3><i class="fas fa-map-marker-alt"></i> Leads by Source</h3></div>
      <div class="crm2-card-body">
        @if($data['sources']->isEmpty())
          <div class="crm2-empty"><i class="fas fa-map-marker-alt"></i><p>No data.</p></div>
        @else
        <table class="crm2-table">
          <thead><tr><th>Source</th><th>Count</th></tr></thead>
          <tbody>
            @foreach($data['sources'] as $row)
            <tr><td>{{ ucwords($row->source ?? 'Unknown') }}</td><td>{{ $row->count }}</td></tr>
            @endforeach
          </tbody>
        </table>
        @endif
      </div>
    </div>
  </div>

  {{-- Activity Report --}}
  @elseif($type === 'activity_report')
  <div class="crm2-card">
    <div class="crm2-card-header"><h3><i class="fas fa-tasks"></i> Activities by Type & Status</h3></div>
    <div class="crm2-card-body">
      @if($data['activities']->isEmpty())
        <div class="crm2-empty"><i class="fas fa-tasks"></i><p>No activities in this period.</p></div>
      @else
      <table class="crm2-table">
        <thead><tr><th>Type</th><th>Status</th><th>Count</th></tr></thead>
        <tbody>
          @foreach($data['activities'] as $row)
          <tr>
            <td><span class="crm2-badge type-{{ $row->type }}">{{ ucfirst($row->type) }}</span></td>
            <td><span class="crm2-badge status-{{ $row->status }}">{{ ucfirst($row->status) }}</span></td>
            <td>{{ $row->count }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
      @endif
    </div>
  </div>

  {{-- Case Report --}}
  @elseif($type === 'case_report')
  <div class="crm2-card">
    <div class="crm2-card-header"><h3><i class="fas fa-headset"></i> Cases by Status & Priority</h3></div>
    <div class="crm2-card-body">
      @if($data['cases']->isEmpty())
        <div class="crm2-empty"><i class="fas fa-headset"></i><p>No cases in this period.</p></div>
      @else
      <table class="crm2-table">
        <thead><tr><th>Status</th><th>Priority</th><th>Count</th></tr></thead>
        <tbody>
          @foreach($data['cases'] as $row)
          <tr>
            <td><span class="crm2-badge status-{{ $row->status }}">{{ ucwords(str_replace('_',' ',$row->status)) }}</span></td>
            <td><span class="crm2-badge priority-{{ $row->priority }}">{{ ucfirst($row->priority) }}</span></td>
            <td>{{ $row->count }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
      @endif
    </div>
  </div>
  @endif
</div>
@endsection
