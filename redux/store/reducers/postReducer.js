import * as types from '../types'

const initialState = {
    posts: [],
    post: {},
    counter: 0,
    loading: true,
    error: null
}

export const  postReducer = (state = initialState, action) => {
    switch (action.type) {
        case types.GET_POSTS:
            return {
                ...state,
                posts: action.payload,
                loading: false,
                error: null
            }
        case types.INCREMENT_COUNTER:
            return {
                ...state,
                counter: state.counter + 1
            }
        default: return state;

    }
}