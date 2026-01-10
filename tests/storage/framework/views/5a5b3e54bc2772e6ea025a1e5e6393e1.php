

<?php $__env->startSection('title', $folder->name . ' - CloudBOX'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-white">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('cloudbox.folders.index')); ?>">Folders</a></li>
                    <?php if($folder->parent): ?>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('cloudbox.folders.show', $folder->parent->id)); ?>"><?php echo e($folder->parent->name); ?></a></li>
                    <?php endif; ?>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo e($folder->name); ?></li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="header-title">
                        <div class="d-flex align-items-center">
                            <i class="ri-folder-3-fill font-size-24 mr-2" style="color: <?php echo e($folder->color ?? '#3498db'); ?>"></i>
                            <div>
                                <h4 class="card-title mb-0"><?php echo e($folder->name); ?></h4>
                                <?php if($folder->description): ?>
                                    <small class="text-muted"><?php echo e($folder->description); ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-header-toolbar d-flex align-items-center">
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown">
                                <i class="ri-add-line"></i> New
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#createSubfolderModal">
                                    <i class="ri-folder-add-line"></i> New Subfolder
                                </a>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#uploadFileModal">
                                    <i class="ri-file-upload-line"></i> Upload File
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo e(session('success')); ?>

                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <!-- Subfolders Section -->
                    <?php if($folder->children->count() > 0): ?>
                        <h5 class="mb-3"><i class="ri-folder-line"></i> Subfolders (<?php echo e($folder->children->count()); ?>)</h5>
                        <!-- Bulk Actions Bar for Subfolders -->
                        <div id="bulkActionsBarSubfolders" class="alert alert-primary d-none mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span><strong><span id="selectedCountSubfolders">0</span> subfolder(s) selected</strong></span>
                                <div>
                                    <button type="button" class="btn btn-sm btn-danger" id="bulkDeleteBtnSubfolders">
                                        <i class="ri-delete-bin-line"></i> Delete Selected
                                    </button>
                                    <button type="button" class="btn btn-sm btn-secondary" id="clearSelectionBtnSubfolders">Clear Selection</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mb-4" style="overflow: visible;">
                            <table class="table table-hover mb-0">
                                <thead class="table-color-heading">
                                    <tr>
                                        <th scope="col" style="width: 40px;">
                                            <input type="checkbox" id="selectAllSubfolders">
                                        </th>
                                        <th scope="col">Folder Name</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Files</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Created</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $folder->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subfolder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="folder-row" data-url="<?php echo e(route('cloudbox.folders.show', $subfolder->id)); ?>">
                                            <td onclick="event.stopPropagation();">
                                                <input type="checkbox" class="subfolder-checkbox" value="<?php echo e($subfolder->id); ?>">
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="ri-folder-3-fill font-size-20" style="color: <?php echo e($subfolder->color ?? '#3498db'); ?>"></i>
                                                    <div class="ml-3">
                                                        <span class="font-weight-bold"><?php echo e($subfolder->name); ?></span>
                                                        <?php if($subfolder->is_favorite): ?>
                                                            <i class="ri-star-fill text-warning ml-1"></i>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if($subfolder->description): ?>
                                                    <span class="text-muted"><?php echo e(Str::limit($subfolder->description, 50)); ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">—</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php echo e($subfolder->files_count); ?> files
                                            </td>
                                            <td>
                                                <?php if($subfolder->is_public): ?>
                                                    <span class="badge badge-success">
                                                        <i class="ri-global-line"></i> Public
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge badge-secondary">
                                                        <i class="ri-lock-line"></i> Private
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($subfolder->created_at->diffForHumans()); ?></td>
                                            <td class="actions-cell">
                                                <div class="dropdown">
                                                    <span class="action-dots" id="dropdownSubfolder<?php echo e($subfolder->id); ?>" data-toggle="dropdown">
                                                        <i class="ri-more-2-fill font-size-20"></i>
                                                    </span>
                                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownSubfolder<?php echo e($subfolder->id); ?>">
                                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editSubfolderModal<?php echo e($subfolder->id); ?>" onclick="event.preventDefault();">
                                                            <i class="ri-pencil-fill mr-2"></i>Edit
                                                        </a>
                                                        <form action="<?php echo e(route('cloudbox.folders.favorite', $subfolder->id)); ?>" method="POST" style="display: inline; width: 100%;">
                                                            <?php echo csrf_field(); ?>
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="ri-star-<?php echo e($subfolder->is_favorite ? 'fill' : 'line'); ?> mr-2 text-warning"></i>
                                                                <?php echo e($subfolder->is_favorite ? 'Remove from Favorites' : 'Add to Favorites'); ?>

                                                            </button>
                                                        </form>
                                                        <a class="dropdown-item" href="#" onclick="event.preventDefault();">
                                                            <i class="ri-share-line mr-2"></i>Share
                                                        </a>
                                                        <a class="dropdown-item" href="#" onclick="event.preventDefault();">
                                                            <i class="ri-file-download-fill mr-2"></i>Download
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        <form action="<?php echo e(route('cloudbox.folders.destroy', $subfolder->id)); ?>" method="POST" style="display: inline; width: 100%;" class="js-delete-form">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" class="dropdown-item text-danger js-delete-btn">
                                                                <i class="ri-delete-bin-6-fill mr-2"></i>Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Edit Subfolder Modal -->
                                        <div class="modal fade" id="editSubfolderModal<?php echo e($subfolder->id); ?>" tabindex="-1" role="dialog">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Folder</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="<?php echo e(route('cloudbox.folders.update', $subfolder->id)); ?>" method="POST">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('PUT'); ?>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="name<?php echo e($subfolder->id); ?>">Folder Name <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" id="name<?php echo e($subfolder->id); ?>" name="name" value="<?php echo e($subfolder->name); ?>" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="color<?php echo e($subfolder->id); ?>">Folder Color</label>
                                                                <input type="color" class="form-control" id="color<?php echo e($subfolder->id); ?>" name="color" value="<?php echo e($subfolder->color ?? '#3498db'); ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="description<?php echo e($subfolder->id); ?>">Description</label>
                                                                <textarea class="form-control" id="description<?php echo e($subfolder->id); ?>" name="description" rows="3"><?php echo e($subfolder->description); ?></textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Privacy Settings <span class="text-danger">*</span></label>
                                                                <div class="custom-control custom-radio mb-2">
                                                                    <input type="radio" id="edit_privacy_private<?php echo e($subfolder->id); ?>" name="is_public" value="0" 
                                                                           class="custom-control-input" <?php echo e(!$subfolder->is_public ? 'checked' : ''); ?>>
                                                                    <label class="custom-control-label" for="edit_privacy_private<?php echo e($subfolder->id); ?>">
                                                                        <i class="ri-lock-line"></i> Private
                                                                        <small class="d-block text-muted">Only you can access this folder</small>
                                                                    </label>
                                                                </div>
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" id="edit_privacy_public<?php echo e($subfolder->id); ?>" name="is_public" value="1" 
                                                                           class="custom-control-input" <?php echo e($subfolder->is_public ? 'checked' : ''); ?>>
                                                                    <label class="custom-control-label" for="edit_privacy_public<?php echo e($subfolder->id); ?>">
                                                                        <i class="ri-global-line"></i> Public
                                                                        <small class="d-block text-muted">Anyone with the link can view this folder</small>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary">Update Folder</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        <hr>
                    <?php endif; ?>

                    <!-- Files Section -->
                    <h5 class="mb-3"><i class="ri-file-line"></i> Files (<?php echo e($folder->files->count()); ?>)</h5>
                    
                    <?php if($folder->files->count() > 0): ?>
                        <!-- Bulk Actions Bar for Files -->
                        <div id="bulkActionsBarFiles" class="alert alert-primary d-none mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span><strong><span id="selectedCountFiles">0</span> file(s) selected</strong></span>
                                <div>
                                    <button type="button" class="btn btn-sm btn-danger" id="bulkDeleteBtnFiles">
                                        <i class="ri-delete-bin-line"></i> Delete Selected
                                    </button>
                                    <button type="button" class="btn btn-sm btn-secondary" id="clearSelectionBtnFiles">Clear Selection</button>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive" style="overflow: visible;">
                            <table class="table table-hover mb-0">
                                <thead class="table-color-heading">
                                    <tr>
                                        <th scope="col" style="width: 40px;">
                                            <input type="checkbox" id="selectAllFiles">
                                        </th>
                                        <th scope="col">File Name</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Size</th>
                                        <th scope="col">Modified</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $folder->files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td onclick="event.stopPropagation();">
                                                <input type="checkbox" class="file-checkbox" value="<?php echo e($file->id); ?>">
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php
                                                        $iconClass = 'ri-file-line';
                                                        $iconColor = 'text-muted';
                                                        if(Str::contains($file->mime_type, 'pdf')) {
                                                            $iconClass = 'ri-file-pdf-line';
                                                            $iconColor = 'text-danger';
                                                        } elseif(Str::contains($file->mime_type, 'word')) {
                                                            $iconClass = 'ri-file-word-line';
                                                            $iconColor = 'text-primary';
                                                        } elseif(Str::contains($file->mime_type, 'excel') || Str::contains($file->mime_type, 'spreadsheet')) {
                                                            $iconClass = 'ri-file-excel-line';
                                                            $iconColor = 'text-success';
                                                        } elseif(Str::contains($file->mime_type, 'image')) {
                                                            $iconClass = 'ri-image-line';
                                                            $iconColor = 'text-info';
                                                        } elseif(Str::contains($file->mime_type, 'video')) {
                                                            $iconClass = 'ri-video-line';
                                                            $iconColor = 'text-warning';
                                                        } elseif(Str::contains($file->mime_type, 'zip') || Str::contains($file->mime_type, 'rar')) {
                                                            $iconClass = 'ri-file-zip-line';
                                                            $iconColor = 'text-secondary';
                                                        }
                                                    ?>
                                                    <i class="<?php echo e($iconClass); ?> font-size-20 <?php echo e($iconColor); ?> mr-2"></i>
                                                    <div>
                                                        <a href="<?php echo e(route('cloudbox.files.view', $file->id)); ?>" class="font-weight-500">
                                                            <?php echo e($file->original_name); ?>

                                                        </a>
                                                        <?php if($file->is_favorite): ?>
                                                            <i class="ri-star-fill text-warning ml-1"></i>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo e(strtoupper($file->extension)); ?></td>
                                            <td><?php echo e($file->formatted_size); ?></td>
                                            <td><?php echo e($file->updated_at->diffForHumans()); ?></td>
                                            <td class="actions-cell">
                                                <div class="dropdown">
                                                    <span class="action-dots" id="dropdownFile<?php echo e($file->id); ?>" data-toggle="dropdown">
                                                        <i class="ri-more-2-fill font-size-20"></i>
                                                    </span>
                                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownFile<?php echo e($file->id); ?>">
                                                        <a class="dropdown-item" href="<?php echo e(route('cloudbox.files.view', $file->id)); ?>">
                                                            <i class="ri-eye-line mr-2"></i>View
                                                        </a>
                                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editFileModal<?php echo e($file->id); ?>" onclick="event.preventDefault();">
                                                            <i class="ri-pencil-fill mr-2"></i>Edit
                                                        </a>
                                                        <form action="<?php echo e(route('cloudbox.files.favorite', $file->id)); ?>" method="POST" style="display: inline; width: 100%;">
                                                            <?php echo csrf_field(); ?>
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="ri-star-<?php echo e($file->is_favorite ? 'fill' : 'line'); ?> mr-2 text-warning"></i>
                                                                <?php echo e($file->is_favorite ? 'Remove from Favorites' : 'Add to Favorites'); ?>

                                                            </button>
                                                        </form>
                                                        <a class="dropdown-item" href="#" onclick="event.preventDefault();">
                                                            <i class="ri-share-line mr-2"></i>Share
                                                        </a>
                                                        <a class="dropdown-item" href="<?php echo e(route('cloudbox.files.download', $file->id)); ?>">
                                                            <i class="ri-file-download-fill mr-2"></i>Download
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        <form action="<?php echo e(route('cloudbox.files.delete', $file->id)); ?>" method="POST" style="display: inline; width: 100%;" class="js-delete-form">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" class="dropdown-item text-danger js-delete-btn">
                                                                <i class="ri-delete-bin-6-fill mr-2"></i>Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Edit File Modal -->
                                        <div class="modal fade" id="editFileModal<?php echo e($file->id); ?>" tabindex="-1" role="dialog">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Rename File</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="<?php echo e(route('cloudbox.files.update', $file->id)); ?>" method="POST">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('PUT'); ?>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="file_name<?php echo e($file->id); ?>">File Name <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" id="file_name<?php echo e($file->id); ?>" 
                                                                       name="name" value="<?php echo e(pathinfo($file->original_name, PATHINFO_FILENAME)); ?>" required>
                                                                <small class="form-text text-muted">Extension: .<?php echo e($file->extension); ?></small>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary">Rename</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="ri-file-list-line font-size-64 text-muted"></i>
                            <p class="text-muted mt-3">No files in this folder yet</p>
                            <button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#uploadFileModal">
                                <i class="ri-file-upload-line"></i> Upload Files
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Subfolder Modal -->
<div class="modal fade" id="createSubfolderModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Subfolder</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="<?php echo e(route('cloudbox.folders.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="parent_id" value="<?php echo e($folder->id); ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="subfolder_name">Folder Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="subfolder_name" name="name" required>
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group">
                        <label for="subfolder_color">Folder Color</label>
                        <input type="color" class="form-control" id="subfolder_color" name="color" value="<?php echo e($folder->color ?? '#3498db'); ?>">
                    </div>
                    <div class="form-group">
                        <label for="subfolder_description">Description</label>
                        <textarea class="form-control" id="subfolder_description" name="description" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Privacy Settings <span class="text-danger">*</span></label>
                        <div class="custom-control custom-radio mb-2">
                            <input type="radio" id="subfolder_privacy_private" name="is_public" value="0" class="custom-control-input" checked>
                            <label class="custom-control-label" for="subfolder_privacy_private">
                                <i class="ri-lock-line"></i> Private
                                <small class="d-block text-muted">Only you can access this folder</small>
                            </label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" id="subfolder_privacy_public" name="is_public" value="1" class="custom-control-input">
                            <label class="custom-control-label" for="subfolder_privacy_public">
                                <i class="ri-global-line"></i> Public
                                <small class="d-block text-muted">Anyone with the link can view this folder</small>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Subfolder</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .folder-row {
        cursor: pointer;
        transition: background-color 0.2s ease;
    }
    .folder-row:hover {
        background-color: #f8f9fa;
    }
    .action-dots {
        cursor: pointer !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
    }
    .action-dots:hover {
        background-color: rgba(0, 0, 0, 0.08);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }
    .action-dots i {
        transition: color 0.3s ease;
    }
    .action-dots:hover i {
        color: #000;
    }
    .actions-cell {
        position: relative;
        overflow: visible !important;
    }
    .actions-cell .dropdown {
        position: relative;
    }
    .actions-cell .dropdown.show {
        z-index: 10000;
    }
    .actions-cell .dropdown-menu {
        position: absolute !important;
        top: calc(100% + 5px) !important;
        right: -10px !important;
        left: auto !important;
        z-index: 10001 !important;
        background-color: #ffffff !important;
        border: 1px solid #dee2e6 !important;
        border-radius: 0.375rem !important;
        box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,.2) !important;
        min-width: 13rem !important;
        margin: 0 !important;
        padding: 0.75rem 0 !important;
    }
    .actions-cell .dropdown-menu::before {
        content: '';
        position: absolute;
        top: -20px;
        right: 0;
        left: 0;
        height: 20px;
        background-color: transparent;
    }
    .actions-cell .dropdown-menu::after {
        content: '';
        position: absolute;
        top: -1px;
        right: -1px;
        bottom: -1px;
        left: -1px;
        background-color: #ffffff;
        border-radius: 0.375rem;
        z-index: -1;
    }
    .dropdown-item {
        cursor: pointer;
        padding: 0.5rem 1.5rem !important;
        clear: both;
        font-weight: 400;
        color: #212529 !important;
        text-align: inherit;
        white-space: nowrap;
        background-color: #fff !important;
        border: 0;
        display: block;
        width: 100%;
        text-decoration: none;
    }
    .dropdown-item:hover {
        background-color: #f8f9fa !important;
        color: #16181b;
    }
    .dropdown-divider {
        height: 0;
        margin: 0.5rem 0;
        overflow: hidden;
        border-top: 1px solid #e9ecef;
    }
/* Upload preview success (not auto-dismiss) */
.upload-preview-success {
    background-color: #f6fef9;
    color: #218838;
    border-radius: 6px;
    padding: 16px 18px 12px 18px;
    margin-bottom: 0;
    font-size: 1rem;
    box-shadow: none;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

    // Auto-dismiss alerts after 5 seconds (but not bulk actions bars)
    setTimeout(function() {
        $('.alert:not(#bulkActionsBar):not(#bulkActionsBarFiles):not(#bulkActionsBarSubfolders):not(.alert-light)').fadeOut('slow');
    }, 5000);

    // Set folder_id in upload modal (luôn đúng folder hiện tại khi mở modal)
    $(document).on('click', '[data-target="#uploadFileModal"]', function() {
        setTimeout(function() {
            $('#upload_folder_id').val('<?php echo e($folder->id); ?>');
        }, 100);
    });

    // Reopen subfolder modal if there are validation errors
    <?php if($errors->any()): ?>
        $('#createSubfolderModal').modal('show');
    <?php endif; ?>

    // Handle folder row click
    $(document).on('click', '.folder-row', function(e) {
        // Don't navigate if clicking on actions cell or its children
        if ($(e.target).closest('.actions-cell').length === 0) {
            var url = $(this).data('url');
            if (url) {
                window.location.href = url;
            }
        }
    });

    // Prevent row click when clicking dropdown
    $(document).on('click', '.actions-cell', function(e) {
        e.stopPropagation();
    });

    // Ensure delete buttons submit forms and don't trigger row navigation
    $(document).on('click', '.js-delete-btn', function(e) {
        e.stopPropagation();
        // Submit the closest form explicitly to avoid any interference
        var form = $(this).closest('form');
        if (form && form.length) {
            form.trigger('submit');
        }
    });

    // Handle action dots click
    $(document).on('click', '.action-dots', function(e) {
        e.preventDefault();
        e.stopPropagation();
    });

    // Bulk selection handlers for files
    const fileCheckboxes = document.querySelectorAll('.file-checkbox');
    const selectAllFiles = document.getElementById('selectAllFiles');
    const bulkActionsBarFiles = document.getElementById('bulkActionsBarFiles');
    const selectedCountFiles = document.getElementById('selectedCountFiles');
    const bulkDeleteBtnFiles = document.getElementById('bulkDeleteBtnFiles');
    const clearSelectionBtnFiles = document.getElementById('clearSelectionBtnFiles');

    function updateBulkActionsFiles() {
        const checked = document.querySelectorAll('.file-checkbox:checked');
        if (checked.length > 0) {
            bulkActionsBarFiles.classList.remove('d-none');
            selectedCountFiles.textContent = checked.length;
        } else {
            bulkActionsBarFiles.classList.add('d-none');
        }
    }

    // Select all checkbox for files
    if (selectAllFiles) {
        selectAllFiles.addEventListener('change', function() {
            fileCheckboxes.forEach(cb => {
                cb.checked = this.checked;
            });
            updateBulkActionsFiles();
        });
    }

    // Individual checkboxes for files
    fileCheckboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            updateBulkActionsFiles();
            // Update select all state
            const allChecked = Array.from(fileCheckboxes).every(c => c.checked);
            const someChecked = Array.from(fileCheckboxes).some(c => c.checked);
            if (selectAllFiles) {
                selectAllFiles.checked = allChecked;
                selectAllFiles.indeterminate = someChecked && !allChecked;
            }
        });
    });

    // Clear selection for files
    if (clearSelectionBtnFiles) {
        clearSelectionBtnFiles.addEventListener('click', function() {
            fileCheckboxes.forEach(cb => cb.checked = false);
            if (selectAllFiles) selectAllFiles.checked = false;
            updateBulkActionsFiles();
        });
    }

    // Bulk delete for files
    if (bulkDeleteBtnFiles) {
        bulkDeleteBtnFiles.addEventListener('click', function() {
            const checked = Array.from(document.querySelectorAll('.file-checkbox:checked'));
            if (checked.length === 0) return;

            const count = checked.length;
            if (!confirm(`Move ${count} file(s) to trash?`)) return;

            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?php echo e(route("cloudbox.files.bulk-delete")); ?>';
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '<?php echo e(csrf_token()); ?>';
            form.appendChild(csrfInput);

            checked.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'file_ids[]';
                input.value = cb.value;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        });
    }

    // Bulk selection handlers for subfolders
    const subfolderCheckboxes = document.querySelectorAll('.subfolder-checkbox');
    const selectAllSubfolders = document.getElementById('selectAllSubfolders');
    const bulkActionsBarSubfolders = document.getElementById('bulkActionsBarSubfolders');
    const selectedCountSubfolders = document.getElementById('selectedCountSubfolders');
    const bulkDeleteBtnSubfolders = document.getElementById('bulkDeleteBtnSubfolders');
    const clearSelectionBtnSubfolders = document.getElementById('clearSelectionBtnSubfolders');

    function updateBulkActionsSubfolders() {
        const checked = document.querySelectorAll('.subfolder-checkbox:checked');
        if (checked.length > 0) {
            bulkActionsBarSubfolders.classList.remove('d-none');
            selectedCountSubfolders.textContent = checked.length;
        } else {
            bulkActionsBarSubfolders.classList.add('d-none');
        }
    }

    // Select all checkbox
    if (selectAllSubfolders) {
        selectAllSubfolders.addEventListener('change', function() {
            subfolderCheckboxes.forEach(cb => {
                cb.checked = this.checked;
            });
            updateBulkActionsSubfolders();
        });
    }

    // Individual checkboxes
    function attachSubfolderCheckboxListeners() {
        subfolderCheckboxes.forEach(cb => {
            cb.removeEventListener('change', subfolderCheckboxChangeHandler);
            cb.addEventListener('change', subfolderCheckboxChangeHandler);
        });
    }
    function subfolderCheckboxChangeHandler() {
        updateBulkActionsSubfolders();
        // Update select all state
        const allChecked = Array.from(subfolderCheckboxes).every(c => c.checked);
        const someChecked = Array.from(subfolderCheckboxes).some(c => c.checked);
        if (selectAllSubfolders) {
            selectAllSubfolders.checked = allChecked;
            selectAllSubfolders.indeterminate = someChecked && !allChecked;
        }
    }
    attachSubfolderCheckboxListeners();

    // Clear selection
    if (clearSelectionBtnSubfolders) {
        clearSelectionBtnSubfolders.addEventListener('click', function() {
            subfolderCheckboxes.forEach(cb => cb.checked = false);
            if (selectAllSubfolders) selectAllSubfolders.checked = false;
            updateBulkActionsSubfolders();
            // Đảm bảo khi clear xong, chọn lại sẽ luôn hiện bulk bar
            attachSubfolderCheckboxListeners();
        });
    }

    // Bulk delete
    if (bulkDeleteBtnSubfolders) {
        bulkDeleteBtnSubfolders.addEventListener('click', function() {
            const checked = Array.from(document.querySelectorAll('.subfolder-checkbox:checked'));
            if (checked.length === 0) return;

            const count = checked.length;
            if (!confirm(`Move ${count} subfolder(s) to trash?`)) return;

            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?php echo e(route("cloudbox.folders.bulk-delete")); ?>';
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '<?php echo e(csrf_token()); ?>';
            form.appendChild(csrfInput);

            checked.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'folder_ids[]';
                input.value = cb.value;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        });
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\cloudbox-laravel\resources\views/pages/folder-view.blade.php ENDPATH**/ ?>