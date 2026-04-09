<div class="max-w-2xl mx-auto mt-10">
    <div class="bg-white shadow-md rounded-lg border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-800">
                    Gestión de cotización
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Cotización {{ $quotation->number }}
                </p>
            </div>
            <a href="{{ route('admin.quotations') }}"
               class="text-sm font-semibold text-blue-600 hover:text-blue-800">
                Volver al listado
            </a>
        </div>

        <form wire:submit.prevent="save" class="px-6 py-6 space-y-6">
            <div>
                <label for="quotation_status_id" class="block text-sm font-medium text-gray-700 mb-1">
                    Estado de cotización *
                </label>
                <select
                    wire:model.defer="quotation_status_id"
                    id="quotation_status_id"
                    class="w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-gray-900 text-sm px-4 py-2.5 bg-white"
                >
                    <option value="">Seleccione un estado</option>
                    @foreach($quotationStatuses as $status)
                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                    @endforeach
                </select>
                @error('quotation_status_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                    Descripción
                </label>
                <textarea
                    wire:model.defer="description"
                    id="description"
                    rows="6"
                    class="w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-gray-900 text-sm px-4 py-2.5"
                    placeholder="Descripción de la cotización"
                ></textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.quotations') }}"
                   class="px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-md">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-500 hover:bg-blue-700 rounded-md shadow-sm">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
