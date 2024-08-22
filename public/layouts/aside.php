<aside
  class="-translate-x-60 md:translate-x-0 w-56 row-start-1 row-end-3 bg-white z-50 h-full fixed top-0 left-0 bottom-0 sm:w-20 transition-all duration-300 hover:w-56 group side-bar-menu"
>
  <div class="absolute -right-3.5 top-6 z-50 md:hidden">
    <button
      class="size-8 flex items-center justify-center rounded-full bg-blue-600 menu-button text-white"
    >
      <i class="fa-solid fa-x"></i>
    </button>
  </div>
  <div class="w-full h-full relative overflow-hidden">
    <div class="flex flex-col w-full relative h-full">
      <div
        class="flex absolute top-5 left-0 right-0 items-center gap-3 font-bold text-3xl pl-6 header"
      >
        <div>
          <i class="fa-solid fa-gear"></i>
        </div>
        <h2>Tracker</h2>
      </div>
      <div
        class="flex absolute bottom-5 left-0 right-0 items-center gap-3 font-medium text-[17px] text-[#8A8A8F] pl-8 cursor-pointer logout"
      >
        <div>
          <i class="fa-solid fa-right-from-bracket"></i>
        </div>
        <h2>Logout</h2>
      </div>
      <div class="mx-3 my-auto flex flex-col justify-between h-[80vh]">
        <div class="flex flex-col gap-4">
          <button
            class="flex bg-[#005F59] h-10 nav-ber items-center md:justify-center text-[#E1F38E] rounded px-4 py-2 gap-3 font-medium"
          >
            <i class="fa-solid fa-gear"></i>
            <h2>Dashboard</h2>
          </button>
          <button
            class="flex text-[#8A8A8F] h-10 nav-ber items-center md:justify-center rounded px-4 py-2 gap-3 font-medium"
          >
            <i class="fa-solid fa-gear"></i>
            <h2>Analytics</h2>
          </button>
          <button
            class="flex text-[#8A8A8F] h-10 nav-ber items-center md:justify-center rounded px-4 py-2 gap-3 font-medium"
          >
            <i class="fa-solid fa-gear"></i>
            <h2>Transaction</h2>
          </button>
          <button
            class="flex text-[#8A8A8F] h-10 nav-ber items-center md:justify-center rounded px-4 py-2 gap-3 font-medium"
          >
            <i class="fa-solid fa-gear"></i>
            <h2>Property</h2>
          </button>
          <button
            class="flex text-[#8A8A8F] h-10 nav-ber items-center md:justify-center rounded px-4 py-2 gap-3 font-medium"
          >
            <i class="fa-solid fa-gear"></i>
            <h2>Tenants</h2>
          </button>
          <button
            class="flex text-[#8A8A8F] h-10 nav-ber items-center md:justify-center rounded px-4 py-2 gap-3 font-medium"
          >
            <i class="fa-solid fa-gear"></i>
            <h2>Messages</h2>
          </button>
        </div>
        <div class="flex flex-col gap-4 hidden curryency">
          <button
            class="bg-[#E1F38E] block overflow-hidden text-[#005F59] rounded w-full h-10 py-2 font-medium text-[16px]"
          >
            Upgrade Plan
          </button>
          <div
            class="flex justify-between bg-[#F9F9F9] border border-[#EFEFF4] p-2"
          >
            <button
              class="bg-white px-6 py-1 rounded text-[17px] font-medium"
            >
              Light
            </button>
            <button
              class="px-6 py-1 rounded font-medium text-[17px] text-[#8A8A8F]"
            >
              Dark
            </button>
          </div>
          <div>
            <select
              class="w-full border px-4 py-2 rounded text-[17px] text-[#666666] font-medium"
            >
              <option value="">Select Currency</option>
              <option value="usd">USD</option>
              <option value="euro">EURO</option>
              <option value="bdt">BDT</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
</aside>
