    <footer class="flex justify-center items-center w-full md:pt-[10vh]">
        <section class="bg-[#F2F2F2] md:rounded-2xl md:w-[95vw] w-full">
          <div
            class="px-6 py-12 grid grid-cols-1 md:grid-cols-2 justify-between items-center gap-y-10"
          >
            <div class="space-y-4 md:w-[30vw]">
              <div class="flex items-center gap-2">
                <img
                  src="{{asset('../asserts/footerLogo.svg')}}"
                  alt="Flovide Logo"
                  class="w-50 h-50"
                />
              </div>
              <p class="text-gray-600 text-sm leading-relaxed">
                At Flovide, our mission is to empower individuals and businesses
                with seamless, secure, and affordable financial solutions.
              </p>

              <div class="mt-6">
                <h3 class="font-semibold text-lg mb-3">
                  Subscribe To Our Newsletter
                </h3>
                <form
                  class="flex rounded-full border border-gray-300 overflow-hidden bg-white"
                >
                  <input
                    type="email"
                    placeholder="Enter your email"
                    class="flex-1 px-4 py-2 outline-none text-sm"
                  />
                <span class="p-1 rounded-full bg-white">
                  <button
                  type="submit"
                  class="flex items-center gap-2 bg-gray-900 rounded-full text-white px-5 py-2 text-sm font-medium hover:bg-gray-800 transition"
                >
                  Subscribe
                  <span>
                    <img
                      src="{{asset('../asserts/homepage/arrow-up.svg')}}"
                      alt=""
                      width="20px"
                    />
                  </span>
                </button>
                </span>
                </form>
              </div>
            </div>

            <section class="grid grid-cols-1 gap-y-10  md:grid-cols-3 md:w-[50vw]">
              <div>
                <h4 class="font-semibold mb-4">Quick Links</h4>
                <ul class="space-y-2 text-gray-600 text-sm">
                  <li>
                    <a href="#" class="hover:text-gray-900">Blog and news</a>
                  </li>
                  <li>
                    <a href="#" class="hover:text-gray-900">Mobile app</a>
                  </li>
                  <li>
                    <a href="#" class="hover:text-gray-900">Why choose us?</a>
                  </li>
                  <li>
                    <a href="#" class="hover:text-gray-900">Pricing plan</a>
                  </li>
                </ul>
              </div>

              <div>
                <h4 class="font-semibold mb-4">Our Services</h4>
                <ul class="space-y-2 text-gray-600 text-sm">
                  {{-- <li>
                    <a href="#" class="hover:text-gray-900">Mobile banking</a>
                  </li> --}}
                  <li>
                    <a href="#" class="hover:text-gray-900"
                      >Advanced security</a
                    >
                  </li>
                  {{-- <li>
                    <a href="#" class="hover:text-gray-900">Digital wallet</a>
                  </li> --}}
                  <li>
                    <a href="#" class="hover:text-gray-900">Budgeting tools</a>
                  </li>
                  <li>
                    <a href="#" class="hover:text-gray-900"
                      >Making transactions</a
                    >
                  </li>
                </ul>
              </div>

              <div>
                <h4 class="font-semibold mb-4">Get In Touch</h4>
                <ul class="space-y-2 text-gray-600 text-sm">
                  <li>United Kingdom</li>
                  <li>
                    <a
                      href="mailto:info@flovide.com"
                      class="hover:text-gray-900"
                      >info@flovide.com</a
                    >
                  </li>
                  <li>
                    <a href="tel:+10000000000" class="hover:text-gray-900"
                      >+1 (000) 0000000</a
                    >
                  </li>
                </ul>
              </div>
            </section>
          </div>

          <!-- bottom link section -->
          <div class="border-t border-gray-200 mt-8">
            <div
              class="max-w-7xl mx-auto px-6 py-4 flex flex-col md:flex-row justify-between items-center text-gray-500 text-sm"
            >
              <p>© Flovide 2025</p>
              <div class="flex space-x-4 mt-2 md:mt-0">
                <a href="#" class="hover:text-gray-700">Terms and conditions</a>
                <a href="#" class="hover:text-gray-700">Privacy policy</a>
              </div>
            </div>
          </div>
        </section>
      </footer>