@extends('layouts.app')

@section('content')
<style>
    :root{
        --ink:#1B2A4A;
        --ink-soft:#5B6B8C;
        --accent:#2F6F5E;
        --accent-soft:#E6F2EF;
        --highlight:#D98E04;
        --bg:#F3F5F9;
        --card:#FFFFFF;
        --border:#E1E6EE;
        --danger:#D64545;
        --success:#2F8F5B;
    }
    .settings-page{
        background:var(--bg);
        font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;
        color:var(--ink);
    }

    /* ---- Page header ---- */
    .page-head h2{
        font-weight:700;
        letter-spacing:-0.01em;
        color:var(--ink);
    }
    .page-head p{
        color:var(--ink-soft);
        max-width:640px;
    }

    /* ---- Section cards ---- */
    .section-card{
        background:var(--card);
        border:1px solid var(--border);
        border-radius:14px;
        margin-bottom:1.25rem;
        overflow:hidden;
    }
    .section-card .section-head{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:.75rem;
        padding:1.1rem 1.4rem;
        border-bottom:1px solid var(--border);
        background:linear-gradient(180deg,#fff, #FAFBFD);
    }
    .section-head .head-left{
        display:flex;
        align-items:center;
        gap:.75rem;
    }
    .section-head .icon-badge{
        width:2.4rem;
        height:2.4rem;
        flex:0 0 auto;
        border-radius:10px;
        background:var(--accent-soft);
        color:var(--accent);
        display:flex;
        align-items:center;
        justify-content:center;
    }
    .section-head .icon-badge svg{ width:1.25rem; height:1.25rem; }
    .section-head h5{
        margin:0;
        font-weight:700;
        font-size:1rem;
        color:var(--ink);
    }
    .section-head small{
        color:var(--ink-soft);
        display:block;
        font-size:.8rem;
    }
    .section-head .count-pill{
        font-size:.75rem;
        font-weight:700;
        color:var(--highlight);
        background:#FBF1DF;
        padding:.3rem .7rem;
        border-radius:999px;
        white-space:nowrap;
    }
    .section-body{ padding:1.4rem; }

    /* ---- Table ---- */
    .approvals-table{
        width:100%;
        border-collapse:separate;
        border-spacing:0;
    }
    .approvals-table thead th{
        font-size:.72rem;
        font-weight:700;
        text-transform:uppercase;
        letter-spacing:.07em;
        color:var(--ink-soft);
        padding:.6rem .75rem;
        border-bottom:2px solid var(--border);
        text-align:left;
    }
    .approvals-table tbody td{
        padding:.85rem .75rem;
        font-size:.9rem;
        color:var(--ink);
        vertical-align:middle;
    }
    .approvals-table tbody tr + tr td{
        border-top:1px solid var(--border);
    }
    .approvals-table tbody tr:hover{
        background:#FAFBFD;
    }
    .approvals-table .user-name{
        font-weight:600;
    }
    .approvals-table .user-email{
        color:var(--ink-soft);
        font-size:.85rem;
    }
    .approvals-table .reg-date{
        font-family:'IBM Plex Mono',monospace;
        font-size:.82rem;
        color:var(--ink-soft);
    }
    .btn-approve{
        background:var(--accent);
        border-color:var(--accent);
        color:#fff;
        font-weight:700;
        font-size:.82rem;
        padding:.4rem 1.2rem;
        border-radius:8px;
    }
    .btn-approve:hover{ background:#265d4f; border-color:#265d4f; color:#fff; }

    /* ---- Empty state ---- */
    .empty-state{
        text-align:center;
        padding:2.5rem 1rem;
        color:var(--ink-soft);
    }
    .empty-state .icon-circle{
        width:3.2rem;
        height:3.2rem;
        border-radius:50%;
        background:var(--accent-soft);
        color:var(--accent);
        display:flex;
        align-items:center;
        justify-content:center;
        margin:0 auto 1rem;
    }
    .empty-state .icon-circle svg{ width:1.6rem; height:1.6rem; }
    .empty-state h6{
        color:var(--ink);
        font-weight:700;
        margin-bottom:.25rem;
    }
    .empty-state p{
        font-size:.85rem;
        margin:0;
    }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">

<div class="settings-page py-4">
    <div class="container">

        <div class="page-head mb-3">
            <h2 class="mb-1">Admin dashboard</h2>
            <p class="mb-0">Review and approve new accounts before they can sign in and start creating invoices.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
        @endif

        <div class="section-card">
            <div class="section-head">
                <div class="head-left">
                    <div class="icon-badge">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                    </div>
                    <div>
                        <h5>Pending user approvals</h5>
                        <small>New sign-ups waiting for access</small>
                    </div>
                </div>
                @if(!$pendingUsers->isEmpty())
                    <span class="count-pill">{{ $pendingUsers->count() }} waiting</span>
                @endif
            </div>
            <div class="section-body">
                @if($pendingUsers->isEmpty())
                    <div class="empty-state">
                        <div class="icon-circle">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <h6>All caught up</h6>
                        <p>There are no pending approvals right now. New sign-ups will show up here.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="approvals-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Registered</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingUsers as $u)
                                <tr>
                                    <td class="user-name">{{ $u->name }}</td>
                                    <td class="user-email">{{ $u->email }}</td>
                                    <td class="reg-date">{{ $u->created_at->format('d-M-Y H:i') }}</td>
                                    <td class="text-end">
                                        <form action="{{ route('admin.users.approve', $u->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn-approve">Approve user</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
