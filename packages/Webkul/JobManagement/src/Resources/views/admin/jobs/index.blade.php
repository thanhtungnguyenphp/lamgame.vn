@extends('admin::layouts.content')

@section('page_title')
    {{ __('job_management::app.admin.jobs.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('job_management::app.admin.jobs.title') }}</h1>
            </div>

            <div class="page-action">
                <a href="{{ route('admin.jobs.create') }}" class="btn btn-lg btn-primary">
                    {{ __('job_management::app.admin.jobs.add-title') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            <datagrid-plus src="{{ route('admin.jobs.index') }}">
                <template v-slot:body="{
                    isLoading,
                    available,
                    applied,
                    selectAll,
                    sort,
                    performAction
                }">
                    <div class="table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="grid_head" id="checkbox">
                                        <span class="checkbox">
                                            <input type="checkbox" v-model="selectAll">
                                            <label class="checkbox-view" for="checkbox"></label>
                                        </span>
                                    </th>
                                    <th class="grid_head">{{ __('job_management::app.admin.jobs.id') }}</th>
                                    <th class="grid_head">{{ __('job_management::app.admin.jobs.title') }}</th>
                                    <th class="grid_head">{{ __('job_management::app.admin.jobs.company') }}</th>
                                    <th class="grid_head">{{ __('job_management::app.admin.jobs.status') }}</th>
                                    <th class="grid_head">{{ __('job_management::app.admin.jobs.created-by') }}</th>
                                    <th class="grid_head">{{ __('job_management::app.admin.jobs.created-at') }}</th>
                                    <th class="grid_head">{{ __('job_management::app.admin.jobs.actions') }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($jobs as $job)
                                <tr>
                                    <td>
                                        <span class="checkbox">
                                            <input type="checkbox" v-model="applied" value="{{ $job->id }}">
                                            <label class="checkbox-view"></label>
                                        </span>
                                    </td>
                                    <td>{{ $job->id }}</td>
                                    <td>
                                        <a href="{{ route('admin.jobs.show', $job->id) }}">
                                            {{ $job->name ?: $job->sku }}
                                        </a>
                                    </td>
                                    <td>{{ $job->company_name ?: 'N/A' }}</td>
                                    <td>
                                        <span class="badge {{ $job->status ? 'badge-md-success' : 'badge-md-danger' }}">
                                            {{ $job->status ? __('job_management::app.admin.jobs.published') : __('job_management::app.admin.jobs.unpublished') }}
                                        </span>
                                    </td>
                                    <td>{{ $job->created_by ?: 'System' }}</td>
                                    <td>{{ $job->created_at ? $job->created_at->format('d M Y') : 'N/A' }}</td>
                                    <td class="actions">
                                        <a href="{{ route('admin.jobs.show', $job->id) }}" class="icon eye-icon" title="{{ __('job_management::app.admin.jobs.view') }}"></a>
                                        <a href="{{ route('admin.jobs.edit', $job->id) }}" class="icon pencil-lg-icon" title="{{ __('job_management::app.admin.jobs.edit') }}"></a>
                                        
                                        @if($job->status)
                                            <form method="POST" action="{{ route('admin.jobs.unpublish', $job->id) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="icon unpublish-icon" title="{{ __('job_management::app.admin.jobs.unpublish') }}"></button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.jobs.publish', $job->id) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="icon publish-icon" title="{{ __('job_management::app.admin.jobs.publish') }}"></button>
                                            </form>
                                        @endif
                                        
                                        <form method="POST" action="{{ route('admin.jobs.destroy', $job->id) }}" style="display: inline;" onsubmit="return confirm('{{ __('job_management::app.admin.jobs.delete-confirm') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="icon trash-icon" title="{{ __('job_management::app.admin.jobs.delete') }}"></button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </template>
            </datagrid-plus>
        </div>
    </div>
@stop
