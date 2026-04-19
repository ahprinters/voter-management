<?php
use Livewire\Component;
use App\Models\Voter;
use Livewire\WithPagination;
?>

<div class="p-6">
    <div class="mb-4">
        <input type="text" wire:model.live.debounce.300ms="search"
            placeholder="ভোটার খুঁজুন (নাম, পিতা, বা ভোটার নম্বর দিয়ে)..."
            class="border p-2 rounded w-full shadow-sm focus:ring focus:border-blue-300">
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300">
            <thead class="bg-gray-100">
                <tr class="text-left">
                    <th class="p-3 border">নাম</th>
                    <th class="p-3 border">পিতার নাম</th>
                    <th class="p-3 border">মাতার নাম</th>
                    <th class="p-3 border">বাড়ির নাম</th>
                    <th class="p-3 border">ভোটার নম্বর</th>
                    <th class="p-3 border">অবস্থান</th>
                    <th class="p-3 border text-center">অ্যাকশন</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $voters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $voter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <tr class="border-t hover:bg-gray-50 transition">
                        <td class="p-3 border"><?php echo e($voter->name); ?></td>
                        <td class="p-3 border"><?php echo e($voter->father_name); ?></td>
                        <td class="p-3 border"><?php echo e($voter->mother_name); ?></td>
                        <td class="p-3 border"><?php echo e($voter->house_name); ?></td>
                        <td class="p-3 border font-mono"><?php echo e($voter->voter_number); ?></td>
                        <td class="p-3 border"><?php echo e($voter->current_location); ?></td>
                        <td class="p-3 border text-center">
                            <a href="<?php echo e(route('voters.voter-comments.create', $voter->id)); ?>" wire:navigate>
                                মন্তব্য লিখুন
                            </a>
                        </td>
                    </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <tr>
                        <td colspan="7" class="p-5 text-center text-gray-500">
                            কোনো তথ্য পাওয়া যায়নি।
                        </td>
                    </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <?php echo e($voters->links()); ?>

    </div>
</div><?php /**PATH C:\Users\ahps3\Herd\voter-management\storage\framework/views/livewire/views/e93e7268.blade.php ENDPATH**/ ?>