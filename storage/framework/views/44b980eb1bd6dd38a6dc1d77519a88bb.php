<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">ভোটার তালিকা</h2>
        <a href="<?php echo e(route('voters.create')); ?>" wire:navigate class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition shadow-sm">
            + নতুন ভোটার
        </a>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('message')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?php echo e(session('message')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="mb-4">
        <input type="text" wire:model.live.debounce.300ms="search"
            placeholder="নাম বা ভোটার নম্বর দিয়ে খুঁজুন..."
            class="border p-2 rounded w-full md:w-1/3 shadow-sm focus:ring focus:border-blue-300">
    </div>

    <div class="bg-white overflow-hidden shadow-md sm:rounded-lg border border-gray-200">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="p-3 text-sm font-semibold text-gray-700">নাম ও পিতার নাম</th>
                    <th class="p-3 text-sm font-semibold text-gray-700">ভোটার নম্বর</th>
                    <th class="p-3 text-sm font-semibold text-gray-700">অবস্থান</th>
                    <th class="p-3 text-sm font-semibold text-gray-700 text-center">অ্যাকশন</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $voters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $voter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-3">
                            <div class="font-bold text-gray-800"><?php echo e($voter->name); ?></div>
                            <div class="text-xs text-gray-500">পিতা: <?php echo e($voter->father_name); ?></div>
                        </td>
                        <td class="p-3 font-mono text-sm"><?php echo e($voter->voter_number); ?></td>
                        <td class="p-3 text-sm text-gray-600"><?php echo e($voter->current_location); ?></td>
                        <td class="p-3">
                            <div class="flex justify-center space-x-2">
                                <a href="<?php echo e(route('voters.voter-comments.create', $voter->id)); ?>" wire:navigate title="মন্তব্য" class="p-1.5 bg-green-100 text-green-600 rounded hover:bg-green-200">
                                    💬
                                </a>

                                <a href="<?php echo e(route('voters.edit', $voter->id)); ?>" wire:navigate title="এডিট" class="p-1.5 bg-blue-100 text-blue-600 rounded hover:bg-blue-200">
                                    ✏️
                                </a>

                                <button wire:click="deleteVoter(<?php echo e($voter->id); ?>)"
                                    wire:confirm="আপনি কি নিশ্চিতভাবে এই ভোটারটি ডিলিট করতে চান?"
                                    title="ডিলিট" class="p-1.5 bg-red-100 text-red-600 rounded hover:bg-red-200">
                                    🗑️
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <tr>
                        <td colspan="4" class="p-10 text-center text-gray-400">কোনো ভোটার পাওয়া যায়নি।</td>
                    </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <?php echo e($voters->links()); ?>

    </div>
</div>
<?php /**PATH C:\Users\ahps3\Herd\voter-management\resources\views/livewire/voter-list.blade.php ENDPATH**/ ?>