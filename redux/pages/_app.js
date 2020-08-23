import {createWrapper} from 'next-redux-wrapper'
import store from '../store/store'
import {Provider} from 'react-redux'

function MyApp({ Component, pageProps }) {
  return (
      <Provider store={store}>
        <Component {...pageProps} />
      </Provider>

  )
}
const makestore = () => store;
const wrapper = createWrapper(makestore);
export default wrapper.withRedux(MyApp);
