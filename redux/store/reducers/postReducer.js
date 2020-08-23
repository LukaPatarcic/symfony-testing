import * as types from '../types'

const initialState = {
    posts: [],
    post: {},
    loading: false,
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
        case types.SET_POSTS_LOADING_TRUE:
            return {
                ...state,
                loading: true
            }
        case types.SET_POSTS_LOADING_FALSE:
            return {
                ...state,
                loading: false
            }
        default: return state;

    }
}