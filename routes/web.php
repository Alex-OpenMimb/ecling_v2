<?php

use App\Http\Controllers\GeneralReportController;
use App\Livewire\Client\FormClient;
use App\Livewire\Client\IndexClient;
use App\Livewire\ClientEquipment\FormClientEquipment;
use App\Livewire\ClientEquipment\IndexClientEquipment;
use App\Livewire\ClientEquipment\PhotoClientEquipment;
use App\Livewire\ClientEquipmentCorrective\FormEquipmentCorrective;
use App\Livewire\ClientEquipmentCorrective\IndexEquipmentCorrective;
use App\Livewire\Configuration\Index as ConfigurationIndex;
use App\Livewire\CorrectiveActivity\IndexCorrective;
use App\Livewire\Equipment\FormEquipment;
use App\Livewire\Equipment\IndexEquipment;
use App\Livewire\Event\FormEvent;
use App\Livewire\Event\IndexEvent;
use App\Livewire\GeneralReport\FormGeneralReport;
use App\Livewire\GeneralReport\IndexGeneralReport;
use App\Livewire\Headquarter\FormHeadquarter;
use App\Livewire\Headquarter\IndexHeadquarter;
use App\Livewire\Material\IndexMaterial;
use App\Livewire\Pending\IndexPending;
use App\Livewire\PreventiveActivity\IndexPreventive;
use App\Livewire\PreventiveRoutine\FormPreventiveRoutine;
use App\Livewire\PreventiveRoutine\IndexPreventiveRoutine;
use App\Livewire\Schedule\IndexSchedule;
use App\Livewire\ServiceOrder\FormServiceOrder;
use App\Livewire\ServiceOrder\IndexServiceOrder;
use App\Livewire\Store\IndexStore;
use App\Livewire\EquipmentClass\Form as EquipmentClassForm;
use App\Livewire\EquipmentClass\Index as EquipmentClassIndex;
use App\Livewire\EquipmentClass\Show as EquipmentClassShow;
use App\Livewire\TitlePhoto\Form as TitlePhotoForm;
use App\Livewire\TitlePhoto\Index as TitlePhotoIndex;
use App\Livewire\TitlePhoto\Show as TitlePhotoShow;
use App\Livewire\User\FormUser;
use App\Livewire\User\IndexUser;
use App\Livewire\User\ProfileUser;
use App\Livewire\WebSite\Home;
use Illuminate\Support\Facades\Route;


Route::get('/',Home::class)->name('home');

Route::middleware(['auth'])->prefix('admin')->group( function() {
    Route::get('users',IndexUser::class)->middleware('can:admin.users')->name('admin.users');
    Route::get('create/user',FormUser::class)->middleware('can:admin.create.user')->name('admin.create.user');
    Route::get('edit/{user}/user',FormUser::class)->middleware('can:admin.edit.user')->name('admin.edit.user');
    Route::get('profile',ProfileUser::class)->middleware('can:admin.profile.user')->name('admin.profile.user');

    Route::get('clients', IndexClient::class)->middleware('can:admin.clients')->name('admin.clients');
    Route::get('create/client',FormClient::class)->middleware('can:admin.create.client')->name('admin.create.client');
    Route::get('edit/{client}/client',FormClient::class)->middleware('can:admin.edit.client')->name('admin.edit.client');

    Route::get('headquarters/{client}',IndexHeadquarter::class)->middleware('can:admin.headquarters')->name('admin.headquarters');
    Route::get('headquarters/{client}/create',FormHeadquarter::class)->middleware('can:admin.create.headquarters')->name('admin.create.headquarters');
    Route::get('headquarters/{client}/edit/{headquarter}',FormHeadquarter::class)->middleware('can:admin.edit.headquarters')->name('admin.edit.headquarters');

    Route::get('components',IndexStore::class)->middleware('can:admin.components')->name('admin.components');
    Route::get('materials',IndexMaterial::class)->middleware('can:admin.materials')->name('admin.materials');
    Route::get('equipments',IndexEquipment::class)->middleware('can:admin.equipments')->name('admin.equipments');
    Route::get('equipments/create',FormEquipment::class)->middleware('can:admin.equipments.create')->name('admin.equipments.create');
    Route::get('equipments/{equipment}/edit',FormEquipment::class)->middleware('can:admin.equipments.edit')->name('admin.equipments.edit');

    Route::get('clients-equipments/{client}/{headquarter}',IndexClientEquipment::class)->middleware('can:admin.clients-equipments')->name('admin.clients-equipments');
    Route::get('clients-equipments/{client}/{headquarter}/create',FormClientEquipment::class)->middleware('can:admin.clients-equipments.create')->name('admin.clients-equipments.create');
    Route::get('clients-equipments/{client}/{headquarter}/{client_equipment}/edit',FormClientEquipment::class)->middleware('can:admin.clients-equipments.edit')->name('admin.clients-equipments.edit');
    Route::get('clients-equipments/{client}/{headquarter}/{client_equipment}/photos',PhotoClientEquipment::class)->middleware('can:admin.clients-equipments.photo')->name('admin.clients-equipments.photo');

    Route::get('preventive-activity',IndexPreventive::class)->middleware('can:admin.preventive-activity')->name('admin.preventive-activity');

    Route::get('preventive-routine',IndexPreventiveRoutine::class)->middleware('can:admin.preventive-routine')->name('admin.preventive-routine');
    Route::get('preventive-routine/create',FormPreventiveRoutine::class)->middleware('can:admin.preventive-routine.create')->name('admin.preventive-routine.create');
    Route::get('preventive-routine/{preventive_routine}/edit',FormPreventiveRoutine::class)->middleware('can:admin.preventive-routine.edit')->name('admin.preventive-routine.edit');

    Route::get('corrective-activities',IndexCorrective::class)->middleware('can:admin.corrective-activities')->name('admin.corrective-activities');

    Route::get('schedule',IndexSchedule::class)->middleware('can:admin.schedule')->name('admin.schedule');

    Route::get('corrective-management',IndexEquipmentCorrective::class)->middleware('can:admin.corrective-management')->name('admin.corrective-management');
    Route::get('corrective-management/create',FormEquipmentCorrective::class)->middleware('can:admin.corrective-management.create')->name('admin.corrective-management.create');
    Route::get('corrective-management/{corrective_service_id}/edit',FormEquipmentCorrective::class)->middleware('can:admin.corrective-management.edit')->name('admin.corrective-management.edit');

    Route::get('planner/create/schedule',FormEvent::class)->middleware('can:admin.planner.schedule')->name('admin.planner.schedule');
    Route::get('planner/create/corrective',FormEvent::class)->middleware('can:admin.planner.corrective')->name('admin.planner.corrective');
    Route::get('planner',IndexEvent::class)->middleware('can:admin.planner')->name('admin.planner');

    Route::get('services-order',IndexServiceOrder::class)->middleware('can:admin.service-order')->name('admin.service-order');
    Route::get('services-order/create/schedule',FormServiceOrder::class)->middleware('can:admin.service-order.schedule.create')->name('admin.service-order.schedule.create');
    Route::get('services-order/create/corrective',FormServiceOrder::class)->middleware('can:admin.service-order.corrective.create')->name('admin.service-order.corrective.create');

    Route::get('general-reports/{service_order_id}',IndexGeneralReport::class)->middleware('can:admin.general-reports')->name('admin.general-reports');
    Route::get('general-reports/form/create/{service_order_id}/{general_report_id}',FormGeneralReport::class)->middleware('can:admin.general-reports.create.form')->name('admin.general-reports.create.form');
    Route::get('general-reports/form/edit/{service_order_id}/{general_report_id}',FormGeneralReport::class)->middleware('can:admin.general-reports.edit.form')->name('admin.general-reports.edit.form');
    Route::get('general-reports/form/edit/{general_report_id}',[GeneralReportController::class,'viewDocument'])->name('admin.general-reports.document');
    Route::get('pending',IndexPending::class)->middleware('can:admin.pending')->name('admin.pending');

    Route::get('configurations',ConfigurationIndex::class)->middleware('can:admin.configurations')->name('admin.configurations');
    Route::get('configurations/equipment-class', EquipmentClassIndex::class)->middleware('can:admin.configurations')->name('admin.configurations.equipment-class.index');
    Route::get('configurations/equipment-class/create', EquipmentClassForm::class)->middleware('can:admin.configurations')->name('admin.configurations.equipment-class.create');
    Route::get('configurations/equipment-class/{equipmentClass}/edit', EquipmentClassForm::class)->middleware('can:admin.configurations')->name('admin.configurations.equipment-class.edit');
    Route::get('configurations/equipment-class/{equipmentClass}', EquipmentClassShow::class)->middleware('can:admin.configurations')->name('admin.configurations.equipment-class.show');
    Route::get('configurations/title-photo', TitlePhotoIndex::class)->middleware('can:admin.configurations')->name('admin.configurations.title-photo.index');
    Route::get('configurations/title-photo/create', TitlePhotoForm::class)->middleware('can:admin.configurations')->name('admin.configurations.title-photo.create');
    Route::get('configurations/title-photo/{titlePhoto}/edit', TitlePhotoForm::class)->middleware('can:admin.configurations')->name('admin.configurations.title-photo.edit');
    Route::get('configurations/title-photo/{titlePhoto}', TitlePhotoShow::class)->middleware('can:admin.configurations')->name('admin.configurations.title-photo.show');


});


