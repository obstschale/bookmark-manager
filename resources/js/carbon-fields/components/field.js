/**
 * The external dependencies.
 */
import React from 'react';
import PropTypes from 'prop-types';
import {compose, withHandlers, setStatic} from 'recompose';

/**
 * The internal dependencies.
 */
import Field from 'fields/components/field';
import withStore from 'fields/decorators/with-store';
import withSetup from 'fields/decorators/with-setup';

/**
 * Render a bookmarklet field.
 *
 * @param  {Object}        props
 * @param  {String}        props.name
 * @param  {Object}        props.field
 * @param  {Function}      props.handleChange
 * @return {React.Element}
 */
export const BookmarkletField = ({
                                     name,
                                     field,
                                     handleChange
                                 }) => {
    return <Field field={field}>
        <p id="bookmarkthis-bookmarklet-description">{field.text.description}</p>

        <p className='pressthis-bookmarklet-wrapper'>
            <a className='pressthis-bookmarklet'
               href={field.bookmarklet}>
                <span>
                    {field.text.bookmark_this}
                </span>
            </a>

            <button type="button" className="button pressthis-js-toggle js-show-pressthis-code-wrap"
                    aria-expanded="false"
                    aria-controls="pressthis-code-wrap">
                <span className="dashicons dashicons-clipboard"></span>
                <span className="screen-reader-text">
                    {field.text.bookmarklet_code}
                </span>
            </button>
        </p>

        <div className="hidden js-pressthis-code-wrap clear" id="pressthis-code-wrap" style={{display: 'none'}}>
            <p id="pressthis-code-desc">
                {field.text.copy_code_description}
            </p>
            <p>
                <textarea className="js-pressthis-code" rows="5" cols="120" readonly="readonly"
                          aria-labelledby="pressthis-code-desc">{field.bookmarklet}
                </textarea>
            </p>
        </div>

    </Field>
        ;
}

/**
 * Validate the props.
 *
 * @type {Object}
 */
BookmarkletField.propTypes = {
    name: PropTypes.string,
    field: PropTypes.shape({
        id: PropTypes.string,
        bookmarklet: PropTypes.string,
        text: PropTypes.string,
    }),
    handleChange: PropTypes.func,
};

/**
 * The enhancer.
 *
 * @type {Function}
 */
export const enhance = compose(
    /**
     * Connect to the Redux store.
     */
    withStore(),

    /**
     * Attach the setup hooks.
     */
    withSetup(),

    /**
     * The handlers passed to the component.
     */
    withHandlers({
        handleChange: ({field, setFieldValue}) => ({target: {value}}) => setFieldValue(field.id, value),
    })
);

export default setStatic('type', [
    'bookmarklet',
])(enhance(BookmarkletField));
